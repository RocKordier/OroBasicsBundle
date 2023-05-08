<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\MessageQueue\Extension;

use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\ConfigBundle\Config\ConfigManager;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\SecurityBundle\Authentication\Token\ConsoleToken;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Component\MessageQueue\Consumption\AbstractExtension;
use Oro\Component\MessageQueue\Consumption\Context;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AutoconfigureTag('oro_message_queue.consumption.extension')]
class SecurityExtension extends AbstractExtension
{
    public function __construct(
        private readonly ConfigManager $configManager,
        private readonly EntityManagerInterface $entityManager,
        private readonly TokenStorageInterface $tokenStorage
    ) {}

    public function onPreReceived(Context $context): void
    {
        if (null !== $this->tokenStorage->getToken()) {
            $context->getLogger()->debug('A token is already set. Skip');

            return;
        }
        /** @var User $userConfig */
        $userConfig = $this->configManager->get('ehdev_basics.bg_username');
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $userConfig->getUsername()]);

        if (null === $user) {
            $context->getLogger()->warning('User is not set or does not exist');

            return;
        }

        /** @var Organization $orgConfig */
        $orgConfig = $this->configManager->get('ehdev_basics.bg_organization');
        /** @var Organization|null $organization */
        $organization = $this->entityManager->getRepository(Organization::class)->findOneBy(['id' => $orgConfig->getId()]) ?: $user->getOrganizations()->first();

        $token = new ConsoleToken();
        $token->setUser($user);

        if ($organization) {
            $token->setOrganization($organization);
        } else {
            $context->getLogger()->warning('No Organization found');

            return;
        }

        $this->tokenStorage->setToken($token);

        $context->getLogger()->info('Authenticated user with username {username} and organization {name}.', [
            'username' => $token->getUser()?->getUsername(),
            'name' => $token->getOrganization()->getName(),
        ]);
    }

    public function onPostReceived(Context $context): void
    {
        $this->tokenStorage->setToken(null);
    }
}
