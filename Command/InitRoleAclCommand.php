<?php

namespace EHDev\Bundle\BasicsBundle\Command;

use Oro\Bundle\SecurityBundle\Acl\Exception\InvalidAclMaskException;
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

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Init Oro Roles and Acls');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configLoader = new CumulativeConfigLoader(
            'ehdev_roles',
            new YamlCumulativeFileLoader('Resources/config/ehdev/acl_roles.yml')
        );

        $this->initRoles($output, $configLoader);
        $this->initAcl($output, $configLoader);

        return 0;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface   $output
     * @param \Oro\Component\Config\Loader\CumulativeConfigLoader $configLoader
     */
    protected function initRoles(OutputInterface $output, CumulativeConfigLoader $configLoader)
    {
        $manager      = $this->getManager();
        $persistRoles = [];

        foreach ($configLoader->load() as $resource) {
            foreach ($resource->data as $roleName => $roleConfigData) {
                if (!array_key_exists('label', $roleConfigData)) {
                    $output->writeln('<error>No label for role: '.$roleName.'</error>');
                    continue;
                }

                $label       = $roleConfigData['label'];
                $description = array_key_exists('description', $roleConfigData) ? $roleConfigData['description'] : '';

                if (!$role = $this->getRole($roleName)) {
                    $output->writeln('Create new role: '.$roleName.' ('.$label.' - '.$description.')');
                    $newRole = new Role($roleName);
                    $newRole->setLabel($label);
                    $newRole->setExtendDescription($description);
                    $persistRoles[] = $newRole;
                } else {
                    $role->setLabel($label);
                    $role->setExtendDescription($description);
                    $persistRoles[] = $role;
                }
            }
        }

        foreach ($persistRoles as $role) {
            $manager->persist($role);
        }

        $manager->flush();
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface   $output
     * @param \Oro\Component\Config\Loader\CumulativeConfigLoader $configLoader
     */
    protected function initAcl(OutputInterface $output, CumulativeConfigLoader $configLoader)
    {
        $aclManager = $this->getAclManager();

        if (!$aclManager->isAclEnabled()) {
            $output->writeln('<error>ACL not enabled. No ACL loaded!</error>');

            return;
        }

        foreach ($configLoader->load() as $resource) {
            foreach ($resource->data as $roleName => $roleConfigData) {
                if (($role = $this->getRole($roleName)) && array_key_exists('permissions', $roleConfigData)) {
                    $output->writeln('INIT role: '.$roleName);
                    $sid = $aclManager->getSid($role);
                    foreach ($roleConfigData['permissions'] as $permission => $acls) {
                        try {
                            $this->processPermission($aclManager, $sid, $permission, $acls);
                        } catch (InvalidAclMaskException $e) {
                            $output->writeln('<error>\n\n'.$e->getMessage().'\n</error>');
                        }
                    }
                } else {
                    $output->writeln(
                        '<comment>Role '.$roleName.' doesn\'t exist or role has no permissions. Skipped!</comment>'
                    );
                }
                $aclManager->flush();
            }
        }

        $output->writeln('Completed');
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
