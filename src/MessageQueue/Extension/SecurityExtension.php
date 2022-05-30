<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\MessageQueue\Extension;

use Doctrine\ORM\EntityManagerInterface;
use EHDev\BasicsBundle\Exception\InvalidUserException;
use Oro\Bundle\ConfigBundle\Config\ConfigManager;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\SecurityBundle\Authentication\Token\ConsoleToken;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Component\MessageQueue\Consumption\AbstractExtension;
use Oro\Component\MessageQueue\Consumption\Context;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

        if (!$this->configManager->get('ehdev_basics.bg_username')) {
            return;
        }

        /** @var string $username */
        $username = $this->configManager->get('ehdev_basics.bg_username');
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if (null == $user) {
            throw new InvalidUserException(sprintf('The user "%s" does not exists! Check your configuration.', $username));
        }

        if ($name = $this->configManager->get('ehdev_basics.bg_organization')) {
            /** @var Organization $organization */
            $organization = $this->entityManager->getRepository(Organization::class)->findOneBy(['name' => $name]);
        } else {
            /** @var Organization $organization */
            $organization = $user->getOrganizations()->first();
        }

        $token = new ConsoleToken();
        $token->setUser($user);
        $token->setOrganization($organization);

        $this->tokenStorage->setToken($token);

        $context->getLogger()->info('Authenticated user with username {username} and organization {name}.', [
            'username' => $username,
            'name' => $organization->getName(),
        ]);
    }

    public function onPostReceived(Context $context): void
    {
        $this->tokenStorage->setToken(null);
    }
}
