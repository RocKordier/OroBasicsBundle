<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Command;

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
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;

/**
 * @phpstan-type RoleConfig array{label?: string, description?: string, permissions?: array<string, list<string>>}
 */
#[AsCommand('ehdev:init-role-acl', 'Init Oro Roles and Acls', ['ehdev:initRoleAcl'])]
class InitRoleAclCommand extends Command
{
    public function __construct(
        private readonly AclManager $aclManager,
        private readonly DoctrineHelper $doctrineHelper,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);
        $style->title('[EHDev] Init Oro Roles and Acls');

        $configLoader = new CumulativeConfigLoader(
            'ehdev_roles',
            new YamlCumulativeFileLoader('Resources/config/ehdev/acl_roles.yml')
        );

        $mergedRoles = $this->mergeRoleConfigs($configLoader);

        $this->initRoles($output, $mergedRoles);
        $this->initAcl($output, $mergedRoles);

        return 0;
    }

    /**
     * @return array<string, RoleConfig>
     */
    private function mergeRoleConfigs(CumulativeConfigLoader $configLoader): array
    {
        $mergedRoles = [];

        foreach ($configLoader->load() as $resource) {
            if (!\is_array($resource->data)) {
                continue;
            }

            foreach ($resource->data as $roleName => $rawConfig) {
                $roleConfigData = $this->normalizeRoleConfig($rawConfig);
                $roleName = (string) $roleName;

                if (!isset($mergedRoles[$roleName])) {
                    $mergedRoles[$roleName] = $roleConfigData;
                    continue;
                }

                if (isset($roleConfigData['label'])) {
                    $mergedRoles[$roleName]['label'] = $roleConfigData['label'];
                }
                if (isset($roleConfigData['description'])) {
                    $mergedRoles[$roleName]['description'] = $roleConfigData['description'];
                }

                if (isset($roleConfigData['permissions'])) {
                    $permissions = $mergedRoles[$roleName]['permissions'] ?? [];
                    foreach ($roleConfigData['permissions'] as $permission => $acls) {
                        $permissions[$permission] = isset($permissions[$permission])
                            ? array_values(array_unique(array_merge($permissions[$permission], $acls)))
                            : $acls;
                    }
                    $mergedRoles[$roleName]['permissions'] = $permissions;
                }
            }
        }

        return $mergedRoles;
    }

    /**
     * @return RoleConfig
     */
    private function normalizeRoleConfig(mixed $rawConfig): array
    {
        if (!\is_array($rawConfig)) {
            return [];
        }

        $roleConfig = [];
        if (isset($rawConfig['label']) && \is_scalar($rawConfig['label'])) {
            $roleConfig['label'] = (string) $rawConfig['label'];
        }
        if (isset($rawConfig['description']) && \is_scalar($rawConfig['description'])) {
            $roleConfig['description'] = (string) $rawConfig['description'];
        }
        if (isset($rawConfig['permissions']) && \is_array($rawConfig['permissions'])) {
            $roleConfig['permissions'] = [];
            foreach ($rawConfig['permissions'] as $permission => $acls) {
                $roleConfig['permissions'][(string) $permission] = \is_array($acls)
                    ? array_values(array_map(strval(...), array_filter($acls, \is_scalar(...))))
                    : [];
            }
        }

        return $roleConfig;
    }

    /**
     * @param array<string, RoleConfig> $roles
     */
    private function initRoles(OutputInterface $output, array $roles): void
    {
        $persistRoles = [];

        foreach ($roles as $roleName => $roleConfigData) {
            if (!isset($roleConfigData['label'])) {
                $output->writeln(\sprintf('<error>No label for role: %s</error>', $roleName));
                continue;
            }

            $label = $roleConfigData['label'];
            $description = $roleConfigData['description'] ?? '';

            if (!$role = $this->getRole($roleName)) {
                $output->writeln('Create new role: '.$roleName.' ('.$label.' - '.$description.')');
                $newRole = new Role($roleName);
                $newRole->setLabel($label);
                $newRole->set('extend_description', $description);
                $persistRoles[] = $newRole;
            } else {
                $role->setLabel($label);
                $role->set('extend_description', $description);
                $persistRoles[] = $role;
            }
        }

        foreach ($persistRoles as $role) {
            $this->doctrineHelper->getEntityManagerForClass(Role::class)?->persist($role);
        }

        $this->doctrineHelper->getEntityManagerForClass(Role::class)?->flush();
    }

    /**
     * @param array<string, RoleConfig> $roles
     */
    private function initAcl(OutputInterface $output, array $roles): void
    {
        if (!$this->aclManager->isAclEnabled()) {
            $output->writeln('<error>ACL not enabled. No ACL loaded!</error>');

            return;
        }

        foreach ($roles as $roleName => $roleConfigData) {
            if (($role = $this->getRole($roleName)) && isset($roleConfigData['permissions'])) {
                $output->writeln('INIT role: '.$roleName);
                $sid = $this->aclManager->getSid($role);
                foreach ($roleConfigData['permissions'] as $permission => $acls) {
                    try {
                        $this->processPermission($sid, $permission, $acls);
                    } catch (InvalidAclMaskException $e) {
                        $output->writeln('<error>\n\n'.$e->getMessage().'\n</error>');
                    }
                }
                $this->aclManager->flush();
            } else {
                $output->writeln(
                    '<comment>Role '.$roleName.' doesn\'t exist or role has no permissions. Skipped!</comment>'
                );
            }
        }

        $output->writeln('Completed');
    }

    /**
     * @param list<string> $acls
     */
    private function processPermission(
        SecurityIdentityInterface $sid,
        string $permission,
        array $acls,
    ): void {
        $oId = $this->aclManager->getOid(str_replace('|', ':', $permission));
        $extension = $this->aclManager->getExtensionSelector()->select($oId);
        if (!$extension instanceof AclExtensionInterface) {
            return;
        }

        $maskBuilders = $extension->getAllMaskBuilders();
        foreach ($maskBuilders as $maskBuilder) {
            $mask = $maskBuilder->reset()->get();
            foreach ($acls as $acl) {
                if ($maskBuilder->hasMask('MASK_'.$acl)) {
                    $mask = $maskBuilder->add($acl)->get();
                }
            }

            $this->aclManager->setPermission($sid, $oId, $mask);
        }
    }

    private function getRole(string $roleName): ?Role
    {
        $role = $this->doctrineHelper
            ->getEntityRepositoryForClass(Role::class)
            ->findOneBy(['role' => $roleName]);

        return $role instanceof Role ? $role : null;
    }
}
