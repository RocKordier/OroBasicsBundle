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
    /**
     * @var ConfigManager
     */
    private $configManager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * EnsureUserExtension constructor.
     * @param ConfigManager $configManager
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(ConfigManager $configManager, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->configManager = $configManager;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function onPreReceived(Context $context)
    {
        if (null !== $this->tokenStorage->getToken()) {
            $context->getLogger()->debug('A token is already set. Skip');
            return;
        }

        if (false === $this->configManager->get('ehdev_basics.bg_username')) {
            return;
        }

        $username = $this->configManager->get('ehdev_basics.bg_username');
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if (null === $user) {
            throw new InvalidUserException(sprintf(
                'The user "%s" does not exists! Check your configuration.',
                $username
            ));
        }

        if ($name = $this->configManager->get('ehdev_basics.bg_organization')) {
            $organization = $this->entityManager->getRepository(Organization::class)->findOneBy(['name' => $name]);
        } else {
            $organization = $user->getOrganizations()->first();
        }

        $token = new ConsoleToken();
        $token->setUser($user);
        $token->setOrganizationContext($organization);

        $this->tokenStorage->setToken($token);

        $context->getLogger()->info('Authenticated user with username {username} and organization {name}.', [
            'username' => $username,
            'name'     => $organization->getName()
        ]);
    }

    public function onPostReceived(Context $context)
    {
        $this->tokenStorage->setToken(null);
    }
}
