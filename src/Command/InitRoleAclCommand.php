<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Command;

use Doctrine\ORM\EntityRepository;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\SecurityBundle\Acl\Exception\InvalidAclMaskException;
use Oro\Bundle\SecurityBundle\Acl\Extension\AclExtensionInterface;
use Oro\Bundle\SecurityBundle\Acl\Persistence\AclManager;
use Oro\Bundle\UserBundle\Entity\Role;
use Oro\Component\Config\Loader\CumulativeConfigLoader;
use Oro\Component\Config\Loader\YamlCumulativeFileLoader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;

#[AsCommand('ehdev:init-role-acl', 'Init Oro Roles and Acls', ['ehdev:initRoleAcl'])]
class InitRoleAclCommand extends Command
{
    private EntityRepository $repository;

    public function __construct(
        private readonly AclManager $aclManager,
        private readonly DoctrineHelper $doctrineHelper
    ) {
        $this->repository = $this->doctrineHelper->getEntityRepositoryForClass(Role::class);

        parent::__construct();
    }

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

    private function initRoles(OutputInterface $output, CumulativeConfigLoader $configLoader): void
    {
        $persistRoles = [];

        foreach ($configLoader->load() as $resource) {
            foreach ($resource->data as $roleName => $roleConfigData) {
                if (!array_key_exists('label', $roleConfigData)) {
                    $output->writeln(sprintf('<error>No label for role: %s</error>', $roleName));
                    continue;
                }

                $label = $roleConfigData['label'];
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
            $this->doctrineHelper->getEntityManagerForClass(Role::class)?->persist($role);
        }

        $this->doctrineHelper->getEntityManagerForClass(Role::class)?->flush();
    }

    private function initAcl(OutputInterface $output, CumulativeConfigLoader $configLoader): void
    {
        if (!$this->aclManager->isAclEnabled()) {
            $output->writeln('<error>ACL not enabled. No ACL loaded!</error>');

            return;
        }

        foreach ($configLoader->load() as $resource) {
            foreach ($resource->data as $roleName => $roleConfigData) {
                if (($role = $this->getRole($roleName)) && array_key_exists('permissions', $roleConfigData)) {
                    $output->writeln('INIT role: '.$roleName);
                    $sid = $this->aclManager->getSid($role);
                    foreach ($roleConfigData['permissions'] as $permission => $acls) {
                        try {
                            $this->processPermission($sid, $permission, $acls);
                        } catch (InvalidAclMaskException $e) {
                            $output->writeln('<error>\n\n'.$e->getMessage().'\n</error>');
                        }
                    }
                } else {
                    $output->writeln(
                        '<comment>Role '.$roleName.' doesn\'t exist or role has no permissions. Skipped!</comment>'
                    );
                }
                $this->aclManager->flush();
            }
        }

        $output->writeln('Completed');
    }

    private function processPermission(
        SecurityIdentityInterface $sid,
        string $permission,
        array $acls
    ): void {
        $oId = $this->aclManager->getOid(str_replace('|', ':', $permission));
        $extension = $this->aclManager->getExtensionSelector()->select($oId);
        if (!$extension instanceof AclExtensionInterface) {
            return;
        }

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

            $this->aclManager->setPermission($sid, $oId, $mask);
        }
    }

    private function getRole(string $roleName): ?Role
    {
        /** @var Role|null $role */
        $role = $this->repository->findOneBy(['role' => $roleName]);

        return $role;
    }
}
