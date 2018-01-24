<?php
namespace EHDev\BasicsBundle\Tests\Unit\Command;

use EHDev\BasicsBundle\Command\InitRoleAclCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Tests\Command\CacheClearCommand\Fixture\TestAppKernel;
use Symfony\Component\Console\Tester\CommandTester;

// https://github.com/symfony/symfony/issues/21534
if (!class_exists('\PHPUnit_Framework_TestCase') && class_exists('\PHPUnit\Framework\TestCase')) {
    class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');
}

class InitRoleAclCommandTest extends KernelTestCase
{
    public function testCommand()
    {
        $testDisplay = 'ACL not enabled. No ACL loaded!
';
        $tester = $this->createCommandTester();
        $tester->execute(array(), array());

        $this->assertEquals($testDisplay, $tester->getDisplay());
    }
    
    /**
     * @return CommandTester
     */
    private function createCommandTester()
    {
        $command = new InitRoleAclCommand('ehdev:initRoleAcl');
        $kernel = $this->getKernel();
        $application = new Application($kernel);
        $application->add($command);
        $command = $application->find('ehdev:initRoleAcl');

        return new CommandTester($command);
    }

    private function getDoctrineMock()
    {
        $registryMock = $this->getMockBuilder('Doctrine\Bundle\DoctrineBundle\Registry')
            ->setMethods(['getManager'])
            ->disableOriginalConstructor()
            ->getMock();
        $emMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $registryMock->expects($this->once())
            ->method('getManager')
            ->willReturn($emMock);

        return $registryMock;
    }

    private function getAclMockget()
    {
        $aclMock = $this->getMockBuilder('Oro\Bundle\SecurityBundle\Acl\Persistence\AclManager')
            ->disableOriginalConstructor()
            ->getMock();

        return $aclMock;
    }

    private function getKernel(array $options = array())
    {
        $kernel = new TestAppKernel('test', true);
        $kernel->boot();
        $kernel->getContainer()->set('doctrine', $this->getDoctrineMock());
        $kernel->getContainer()->set('oro_security.acl.manager', $this->getAclMockget());
        return $kernel;
    }
}