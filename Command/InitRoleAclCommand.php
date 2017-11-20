<?php
namespace EHDev\BasicsBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\SecurityBundle\Acl\Exception\InvalidAclMaskException;
use Oro\Bundle\SecurityBundle\Acl\Persistence\AclManager;
use Oro\Bundle\UserBundle\Entity\Role;
use Oro\Component\Config\Loader\CumulativeConfigLoader;
use Oro\Component\Config\Loader\YamlCumulativeFileLoader;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;

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
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
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
    protected function initRoles(OutputInterface $output, CumulativeConfigLoader $configLoader): void
    {
        $manager      = $this->getManager();
        $persistRoles = [];

        foreach ($configLoader->load() as $resource) {
            foreach ($resource->data as $roleName => $roleConfigData) {
                if (!array_key_exists('label', $roleConfigData)) {
                    $output->writeln(sprintf('<error>No label for role: %s</error>', $roleName));
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
    protected function initAcl(OutputInterface $output, CumulativeConfigLoader $configLoader): void
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
    ): void {
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
    
    protected function getRole(string $roleName): ?Role
    {
        return $this->getManager()->getRepository('OroUserBundle:Role')->findOneBy(['role' => $roleName]);
    }
    
    protected function getManager(): ObjectManager
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }
    
    protected function getAclManager(): AclManager
    {
        return $this->getContainer()->get('oro_security.acl.manager');
    }
}
