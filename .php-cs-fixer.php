<?php

declare(strict_types=1);

use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use PhpCsFixerCustomFixers\Fixer\ConstructorEmptyBracesFixer;
use PhpCsFixerCustomFixers\Fixer\MultilinePromotedPropertiesFixer;
use PhpCsFixerCustomFixers\Fixer\NoDuplicatedArrayKeyFixer;
use PhpCsFixerCustomFixers\Fixer\NoDuplicatedImportsFixer;
use PhpCsFixerCustomFixers\Fixer\NoPhpStormGeneratedCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoTrailingCommaInSinglelineFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessDoctrineRepositoryCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessStrlenFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocArrayStyleFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocSelfAccessorFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocTypesTrimFixer;
use PhpCsFixerCustomFixers\Fixer\PhpUnitAssertArgumentsOrderFixer;
use PhpCsFixerCustomFixers\Fixer\PhpUnitDedicatedAssertFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceAfterStatementFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceBeforeStatementFixer;
use PhpCsFixerCustomFixers\Fixer\StringableInterfaceFixer;
use PhpCsFixerCustomFixers\Fixers;

$finder = Finder::create()
    ->in([__DIR__.'/src', __DIR__.'/tests'])
;

return (new PhpCsFixer\Config())
    ->registerCustomFixers(new Fixers())
    ->setFinder($finder)
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'declare_strict_types' => true,
        'nullable_type_declaration_for_default_null_value' => false,
        'phpdoc_separation' => [
            'groups' => [['ORM\\*'], ['Assert\\*']],
        ],
        ConstructorEmptyBracesFixer::name() => true,
        MultilinePromotedPropertiesFixer::name() => true,
        NoDuplicatedImportsFixer::name() => true,
        NoPhpStormGeneratedCommentFixer::name() => true,
        PhpdocSelfAccessorFixer::name() => true,
        PhpdocTypesTrimFixer::name() => true,
        SingleSpaceAfterStatementFixer::name() => true,
        SingleSpaceBeforeStatementFixer::name() => true,
        StringableInterfaceFixer::name() => true,
        NoDuplicatedArrayKeyFixer::name() => true,
        NoTrailingCommaInSinglelineFixer::name() => true,
        NoUselessDoctrineRepositoryCommentFixer::name() => true,
        NoUselessStrlenFixer::name() => true,
        PhpUnitAssertArgumentsOrderFixer::name() => true,
        PhpUnitDedicatedAssertFixer::name() => true,
        PhpdocArrayStyleFixer::name() => true,
    ])
    ->setLineEnding("\n")
    ->setRiskyAllowed(true)
;
