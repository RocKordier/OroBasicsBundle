<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\EventListener;

use Oro\Bundle\InstallerBundle\InstallerEvent;
use Oro\Bundle\InstallerBundle\InstallerEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: InstallerEvents::FINISH)]
final readonly class OroInstallListener
{
    public function __construct(
    ) {}

    public function __invoke(InstallerEvent $event): void
    {
        $event->getCommandExecutor()->runCommand('ehdev:init-role-acl', ['--process-isolation' => true]);
    }
}
