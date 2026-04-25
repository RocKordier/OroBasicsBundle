<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\DependencyInjection\Compiler;

use Oro\Bundle\ActivityBundle\Manager\ActivityManager;
use Oro\Bundle\ActivityBundle\Tools\ActivityAssociationHelper;
use Oro\Bundle\AddressBundle\Form\EventListener\AddressCountryAndRegionSubscriber;
use Oro\Bundle\AttachmentBundle\Manager\AttachmentManager;
use Oro\Bundle\AttachmentBundle\Provider\AttachmentProvider;
use Oro\Bundle\ChartBundle\Model\ChartViewBuilder;
use Oro\Bundle\DataAuditBundle\Provider\AuditConfigProvider;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendDbIdentifierNameGenerator;
use Oro\Bundle\FormBundle\Model\UpdateHandlerFacade;
use Oro\Bundle\NavigationBundle\Provider\TitleServiceInterface;
use Oro\Bundle\SecurityBundle\Acl\Persistence\AclManager;
use Oro\Bundle\UIBundle\Provider\WidgetContextProvider;
use Oro\Bundle\UserBundle\Entity\UserManager;
use Oro\Bundle\UserBundle\Mailer\UserTemplateEmailSender;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AutowireAliasPass implements CompilerPassInterface
{
    private const ALIAS_MAP = [
        'oro_activity.manager' => ActivityManager::class,
        'oro_chart.view_builder' => ChartViewBuilder::class,
        'oro_user.mailer.user_template_email_sender' => UserTemplateEmailSender::class,
        'oro_form.update_handler' => UpdateHandlerFacade::class,
        'oro_attachment.manager' => AttachmentManager::class,
        'oro_attachment.provider.attachment' => AttachmentProvider::class,
        'oro_address.form.listener.address' => AddressCountryAndRegionSubscriber::class,
        'oro_dataaudit.audit_config_provider' => AuditConfigProvider::class,
        'oro_security.acl.manager' => AclManager::class,
        'oro_ui.provider.widget_context' => WidgetContextProvider::class,
        'oro_user.manager' => UserManager::class,
        'oro_activity.association_helper' => ActivityAssociationHelper::class,
        'oro_migration.db_id_name_generator' => ExtendDbIdentifierNameGenerator::class,
        'oro_navigation.title_service' => TitleServiceInterface::class,
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
