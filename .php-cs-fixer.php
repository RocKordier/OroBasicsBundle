<?php

declare(strict_types=1);

use PhpCsFixer\Finder;
use PhpCsFixerCustomFixers\Fixer\NoDuplicatedArrayKeyFixer;
use PhpCsFixerCustomFixers\Fixer\NoTrailingCommaInSinglelineFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessDoctrineRepositoryCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessStrlenFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocArrayStyleFixer;
use PhpCsFixerCustomFixers\Fixer\PhpUnitAssertArgumentsOrderFixer;
use PhpCsFixerCustomFixers\Fixer\PhpUnitDedicatedAssertFixer;
use PhpCsFixerCustomFixers\Fixers;
use PhpCsFixerCustomFixers\Fixer\ConstructorEmptyBracesFixer;
use PhpCsFixerCustomFixers\Fixer\MultilinePromotedPropertiesFixer;
use PhpCsFixerCustomFixers\Fixer\NoDuplicatedImportsFixer;
use PhpCsFixerCustomFixers\Fixer\NoPhpStormGeneratedCommentFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocSelfAccessorFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocTypesTrimFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceAfterStatementFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceBeforeStatementFixer;
use PhpCsFixerCustomFixers\Fixer\StringableInterfaceFixer;

$finder = Finder::create()
    ->in([__DIR__.'/src',])
;

return (new PhpCsFixer\Config())
    ->registerCustomFixers(new Fixers())
    ->setFinder($finder)
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'declare_strict_types' => true,
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
    ->setRiskyAllowed(true)
    ->setLineEnding("\n")
    ;
