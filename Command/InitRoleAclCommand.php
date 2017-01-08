<?php

namespace EHDev\Bundle\BasicsBundle\Command;

use Oro\Bundle\SecurityBundle\Acl\Persistence\AclManager;
use Oro\Bundle\UserBundle\Entity\Role;
use Oro\Component\Config\Loader\CumulativeConfigLoader;
use Oro\Component\Config\Loader\YamlCumulativeFileLoader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;

/**
 * Class InitRoleAclCommand
 *
 * @package EHDev\Bundle\BasicsBundle\Command
 */
class InitRoleAclCommand extends ContainerAwareCommand
{
    const NAME = 'ehdev:initRoleAcl';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Init Oro Roles and Acls');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initRoles($output);
        $this->initAcl($output);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function initRoles(OutputInterface $output)
    {
        $configLoader = new CumulativeConfigLoader(
            'ehdev_roles',
            new YamlCumulativeFileLoader('Resources/config/ehdev/roles.yml')
        );

        $manager      = $this->getManager();
        $persistRoles = [];

        foreach ($configLoader->load() as $resource) {
            foreach ($resource->data as $role => $label) {
                if (!$role = $this->getRole($role)) {
                    $newRole = new Role($role);
                    $newRole->setLabel($label);
                    $persistRoles[] = $newRole;
                } else {
                    $persistRoles[] = $role->setLabel($label);
                }
            }
        }

        foreach ($persistRoles as $role) {
            $manager->persist($role);
        }

        $manager->flush();
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function initAcl(OutputInterface $output)
    {
        $configLoader = new CumulativeConfigLoader(
            'ehdev_acl',
            new YamlCumulativeFileLoader('Resources/config/ehdev/acl.yml')
        );

        $aclManager = $this->getAclManager();

        foreach ($configLoader->load() as $resource) {
            foreach ($resource->data as $roleName => $roleConfigData) {
                if (($role = $this->getRole($roleName)) && $aclManager->isAclEnabled()) {
                    $output->writeln('INIT role: '.$roleName);
                    $sid = $aclManager->getSid($role);
                    foreach ($roleConfigData['permissions'] as $permission => $acls) {
                        $this->processPermission($aclManager, $sid, $permission, $acls);
                    }
                } else {
                    $output->writeln($roleName.' isn\'t inited yet. Please create roles.yml!');
                }
                $aclManager->flush();
            }
        }
    }

    /**
     * @param \Oro\Bundle\SecurityBundle\Acl\Persistence\AclManager           $aclManager
     * @param \Symfony\Component\Security\Acl\Model\SecurityIdentityInterface $sid
     * @param                                                                 $permission
     * @param array                                                           $acls
     */
    protected function processPermission(
        AclManager $aclManager,
        SecurityIdentityInterface $sid,
        $permission,
        array $acls
    ) {
        $oId = $aclManager->getOid(str_replace('|', ':', $permission));

        $extension    = $aclManager->getExtensionSelector()->select($oId);
        $maskBuilders = $extension->getAllMaskBuilders();

        foreach ($maskBuilders as $maskBuilder) {
            $mask = $maskBuilder->reset()->get();
            if (!empty($acls)) {
                foreach ($acls as $acl) {
                    if ($maskBuilder->hasMask('MASK_'.$acl)) {
                        $mask = $maskBuilder->add($acl)->get();
                    }
                }
            }

            $aclManager->setPermission($sid, $oId, $mask);
        }
    }

    /**
     * @param $roleName
     *
     * @return Role|null
     */
    protected function getRole($roleName)
    {
        return $this->getManager()->getRepository('OroUserBundle:Role')->findOneBy(['role' => $roleName]);
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @return object|\Oro\Bundle\SecurityBundle\Acl\Persistence\AclManager
     */
    protected function getAclManager()
    {
        return $this->getContainer()->get('oro_security.acl.manager');
    }
}
