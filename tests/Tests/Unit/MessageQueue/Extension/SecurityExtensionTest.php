<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Unit\MessageQueue\Extension;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use EHDev\BasicsBundle\MessageQueue\Extension\SecurityExtension;
use Oro\Bundle\ConfigBundle\Config\ConfigManager;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\SecurityBundle\Authentication\Token\ConsoleToken;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\UserBundle\Entity\UserManager;
use Oro\Component\MessageQueue\Consumption\Context;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\NullLogger;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

#[CoversClass(SecurityExtension::class)]
final class SecurityExtensionTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy<ConfigManager> */
    private ObjectProphecy $configManager;
    /** @var ObjectProphecy<EntityManagerInterface> */
    private ObjectProphecy $entityManager;
    /** @var ObjectProphecy<TokenStorageInterface> */
    private ObjectProphecy $tokenStorage;
    /** @var ObjectProphecy<UserManager> */
    private ObjectProphecy $userManager;
    private SecurityExtension $extension;

    protected function setUp(): void
    {
        $this->configManager = $this->prophesize(ConfigManager::class);
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->tokenStorage = $this->prophesize(TokenStorageInterface::class);
        $this->userManager = $this->prophesize(UserManager::class);

        $this->extension = new SecurityExtension(
            $this->configManager->reveal(),
            $this->entityManager->reveal(),
            $this->tokenStorage->reveal(),
            $this->userManager->reveal(),
        );
    }

    public function testOnPreReceivedUsesConfiguredOrganization(): void
    {
        $configuredOrganization = new Organization();
        $configuredOrganization->setId(2);
        $organization = new Organization();

        $user = (new User())->setUsername('foo');
        $user->addOrganization(new Organization());

        $this->tokenStorage->getToken()->willReturn(null);
        $this->configManager->get('ehdev_basics.bg_username')->willReturn('foo');
        $this->configManager->get('ehdev_basics.bg_organization')->willReturn($configuredOrganization);
        $this->userManager->findUserByUsername('foo')->willReturn($user);

        $repo = $this->prophesize(EntityRepository::class);
        $repo->findOneBy(['id' => 2])->willReturn($organization);
        $this->entityManager->getRepository(Organization::class)->willReturn($repo->reveal());

        $this->expectTokenFor($user, $organization);

        $this->extension->onPreReceived($this->createContext());
    }

    public function testOnPreReceivedFallsBackToFirstUserOrganization(): void
    {
        $organization = new Organization();
        $user = (new User())->setUsername('foo');
        $user->addOrganization($organization);

        $this->tokenStorage->getToken()->willReturn(null);
        $this->configManager->get('ehdev_basics.bg_username')->willReturn($user);
        $this->configManager->get('ehdev_basics.bg_organization')->willReturn(null);
        $this->userManager->findUserByUsername('foo')->willReturn($user);

        $this->expectTokenFor($user, $organization);

        $this->extension->onPreReceived($this->createContext());
    }

    public function testOnPreReceivedWithoutConfiguredUser(): void
    {
        $this->tokenStorage->getToken()->willReturn(null);
        $this->configManager->get('ehdev_basics.bg_username')->willReturn(null);

        $this->userManager->findUserByUsername(Argument::any())->shouldNotBeCalled();
        $this->tokenStorage->setToken(Argument::any())->shouldNotBeCalled();

        $this->extension->onPreReceived($this->createContext());
    }

    public function testOnPreReceivedWithUnknownUser(): void
    {
        $this->tokenStorage->getToken()->willReturn(null);
        $this->configManager->get('ehdev_basics.bg_username')->willReturn('foo');
        $this->userManager->findUserByUsername('foo')->willReturn(null);

        $this->tokenStorage->setToken(Argument::any())->shouldNotBeCalled();

        $this->extension->onPreReceived($this->createContext());
    }

    public function testOnPreReceivedWithoutOrganization(): void
    {
        $this->tokenStorage->getToken()->willReturn(null);
        $this->configManager->get('ehdev_basics.bg_username')->willReturn('foo');
        $this->configManager->get('ehdev_basics.bg_organization')->willReturn(null);
        $this->userManager->findUserByUsername('foo')->willReturn(new User());

        $this->tokenStorage->setToken(Argument::any())->shouldNotBeCalled();

        $this->extension->onPreReceived($this->createContext());
    }

    public function testOnPreReceivedWithExistingToken(): void
    {
        $token = $this->prophesize(TokenInterface::class);
        $this->tokenStorage->getToken()->willReturn($token->reveal());

        $this->configManager->get(Argument::any())->shouldNotBeCalled();
        $this->tokenStorage->setToken(Argument::any())->shouldNotBeCalled();

        $this->extension->onPreReceived($this->createContext());
    }

    public function testOnPostReceived(): void
    {
        $this->tokenStorage->setToken(null)->shouldBeCalled();

        $this->extension->onPostReceived($this->createContext());
    }

    private function expectTokenFor(User $user, Organization $organization): void
    {
        $this->tokenStorage->setToken(Argument::that(static function (mixed $token) use ($user, $organization): bool {
            return $token instanceof ConsoleToken
                && $token->getUser() === $user
                && $token->getOrganization() === $organization;
        }))->shouldBeCalled();
    }

    private function createContext(): Context
    {
        $context = $this->prophesize(Context::class);
        $context->getLogger()->willReturn(new NullLogger());

        return $context->reveal();
    }
}
