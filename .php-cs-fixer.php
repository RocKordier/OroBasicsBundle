<?php

declare(strict_types=1);

use PhpCsFixer\Finder;
use PhpCsFixerCustomFixers\Fixers;
use PhpCsFixerCustomFixers\Fixer\ConstructorEmptyBracesFixer;
use PhpCsFixerCustomFixers\Fixer\MultilinePromotedPropertiesFixer;
use PhpCsFixerCustomFixers\Fixer\NoDoctrineMigrationsGeneratedCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoDuplicatedImportsFixer;
use PhpCsFixerCustomFixers\Fixer\NoPhpStormGeneratedCommentFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocSelfAccessorFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocTypesTrimFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceAfterStatementFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceBeforeStatementFixer;
use PhpCsFixerCustomFixers\Fixer\StringableInterfaceFixer;


$finder = Finder::create()
    ->in([__DIR__.'/src', __DIR__.'/tests'])
;

return (new PhpCsFixer\Config())
    ->registerCustomFixers(new Fixers())
    ->setFinder($finder)
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        'declare_strict_types' => true,
        ConstructorEmptyBracesFixer::name() => true,
        MultilinePromotedPropertiesFixer::name() => true,
        NoDoctrineMigrationsGeneratedCommentFixer::name() => true,
        NoDuplicatedImportsFixer::name() => true,
        NoPhpStormGeneratedCommentFixer::name() => true,
        PhpdocSelfAccessorFixer::name() => true,
        PhpdocTypesTrimFixer::name() => true,
        SingleSpaceAfterStatementFixer::name() => true,
        SingleSpaceBeforeStatementFixer::name() => true,
        StringableInterfaceFixer::name() => true,
    ])
    ->setRiskyAllowed(true)
    ->setLineEnding("\n")
    ;
