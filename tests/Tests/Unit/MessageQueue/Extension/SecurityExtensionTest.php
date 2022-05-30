<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Unit\MessageQueue\Extension;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use EHDev\BasicsBundle\Exception\InvalidUserException;
use EHDev\BasicsBundle\MessageQueue\Extension\SecurityExtension;
use Oro\Bundle\ConfigBundle\Config\ConfigManager;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\SecurityBundle\Authentication\Token\ConsoleToken;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Component\MessageQueue\Consumption\Context;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\NullLogger;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SecurityExtensionTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|ConfigManager $configManager;
    private ObjectProphecy|EntityManagerInterface $entityManager;
    private ObjectProphecy|TokenStorageInterface$tokenStorage;
    private ObjectProphecy|SecurityExtension $extension;

    protected function setUp(): void
    {
        $this->configManager = $this->prophesize(ConfigManager::class);
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->tokenStorage = $this->prophesize(TokenStorageInterface::class);

        $this->extension = new SecurityExtension(
            $this->configManager->reveal(),
            $this->entityManager->reveal(),
            $this->tokenStorage->reveal()
        );
    }

    public function testOnPreReceived(): void
    {
        $this->configManager
            ->get('ehdev_basics.bg_username')
            ->willReturn('foo');

        $this->configManager
            ->get('ehdev_basics.bg_organization')
            ->willReturn('Google');

        $user = new User();
        $repo = $this->prophesize(EntityRepository::class);
        $repo->findOneBy(['username' => 'foo'])->willReturn($user);

        $this->entityManager->getRepository(User::class)
            ->willReturn($repo->reveal());

        $organization = new Organization();

        $repo = $this->prophesize(EntityRepository::class);
        $repo->findOneBy(['name' => 'Google'])->willReturn($organization);

        $this->entityManager->getRepository(Organization::class)
            ->willReturn($repo->reveal());

        $this->tokenStorage->setToken(Argument::that(function ($token) use ($user, $organization) {
            if (!$token instanceof ConsoleToken) {
                return false;
            }

            self::assertSame($user, $token->getUser());
            self::assertSame($organization, $token->getOrganizationContext());

            return true;
        }))->shouldBeCalled();

        $this->tokenStorage->getToken()->willReturn(null);

        $context = $this->prophesize(Context::class);
        $context->getLogger()->willReturn(new NullLogger());
        $this->extension->onPreReceived($context->reveal());
    }

    public function testOnPreRecievedWithNoUsername(): void
    {
        $this->tokenStorage->getToken()->willReturn(null);

        $this->configManager
            ->get('ehdev_basics.bg_username')
            ->willReturn('');

        $this->tokenStorage->setToken(Argument::type(ConsoleToken::class))
            ->shouldNotBeCalled();

        $context = $this->prophesize(Context::class);
        $context->getLogger()->willReturn(new NullLogger());
        $this->extension->onPreReceived($context->reveal());
    }

    public function testOnPreRecievedWithAlready(): void
    {
        $token = $this->prophesize(TokenInterface::class);
        $this->tokenStorage->getToken()->willReturn($token->reveal());

        $this->configManager->get(Argument::any())
            ->shouldNotBeCalled();

        $context = $this->prophesize(Context::class);
        $context->getLogger()->willReturn(new NullLogger());
        $this->extension->onPreReceived($context->reveal());
    }

    public function testOnPostReceived(): void
    {
        $this->tokenStorage->setToken(null)
            ->shouldBeCalled();

        $context = $this->prophesize(Context::class);
        $this->extension->onPostReceived($context->reveal());
    }

    public function testOnPreReceivedWithInvalidUser(): void
    {
        self::expectException(InvalidUserException::class);

        $repo = $this->prophesize(EntityRepository::class);
        $repo->findOneBy(['username' => 'foo'])->willReturn(null);

        $this->configManager
            ->get('ehdev_basics.bg_username')
            ->willReturn('foo');

        $this->entityManager->getRepository(User::class)
            ->willReturn($repo->reveal());

        $context = $this->prophesize(Context::class);
        $context->getLogger()->willReturn(new NullLogger());
        $this->extension->onPreReceived($context->reveal());
    }
}
