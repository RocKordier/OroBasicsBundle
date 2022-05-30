<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AutowireAliasPass implements CompilerPassInterface
{
    private const ALIAS_MAP = [
        'oro_activity.manager' => 'Oro\Bundle\ActivityBundle\Manager\ActivityManager',
        'oro_chart.view_builder' => 'Oro\Bundle\ChartBundle\Model\ChartViewBuilder',
        'oro_user.mailer.user_template_email_sender' => 'Oro\Bundle\UserBundle\Mailer\UserTemplateEmailSender',
        'oro_form.update_handler' => 'Oro\Bundle\FormBundle\Model\UpdateHandlerFacade',
        'oro_attachment.manager' => 'Oro\Bundle\AttachmentBundle\Manager\AttachmentManager',
        'oro_attachment.provider.attachment' => 'Oro\Bundle\AttachmentBundle\Provider\AttachmentProvider',
        'oro_address.form.listener.address' => 'Oro\Bundle\AddressBundle\Form\EventListener\AddressCountryAndRegionSubscriber',
        'oro_dataaudit.audit_config_provider' => 'Oro\Bundle\DataAuditBundle\Provider\AuditConfigProvider',
        'oro_security.acl.manager' => 'Oro\Bundle\SecurityBundle\Acl\Persistence\AclManager',
    ];

    public function process(ContainerBuilder $container): void
    {
        foreach (self::ALIAS_MAP as $id => $alias) {
            if ($container->has($id) && !$container->hasAlias($alias)) {
                $container->setAlias($alias, $id);
            }
        }
    }
}
