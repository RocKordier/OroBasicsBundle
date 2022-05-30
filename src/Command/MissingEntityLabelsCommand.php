<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Command;

use Doctrine\Common\Persistence\ObjectRepository;
use EHDev\BasicsBundle\Model\PropertyTranslation;
use EHDev\BasicsBundle\Provider\EntityPropertyTranslationProvider;
use Oro\Bundle\EntityConfigBundle\Entity\EntityConfigModel;
use Oro\Bundle\LocaleBundle\Entity\Localization;
use Oro\Bundle\LocaleBundle\Entity\Repository\LocalizationRepository;
use Spatie\Emoji\Emoji;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MissingEntityLabelsCommand extends ContainerAwareCommand
{
    public const NAME = 'ehdev:missing-entity-labels';
    public const ENTITY_CLASS_NAME = 'Oro\Bundle\EntityConfigBundle\Entity\EntityConfigModel';
    public const OPTION_IGNORE_ORO = 'ignore-oro';
    public const OPTION_IGNORE_EXTEND = 'ignore-extend';
    public const OPTION_LOCALES = 'for-locale';
    public const OPTION_ENTITY = 'entity';
    public const OPTION_SHOW_ALL = 'all';

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Shows all Labels which are missing for entities')
            ->addOption(
                self::OPTION_IGNORE_ORO,
                null,
                InputOption::VALUE_NONE,
                'Ignore Entities who lives in the Oro\... Namespace!'
            )
            ->addOption(
                self::OPTION_IGNORE_EXTEND,
                null,
                InputOption::VALUE_NONE,
                'Ignore Entities who lives in the Extend\... Namespace!'
            )
            ->addOption(
                self::OPTION_SHOW_ALL,
                null,
                InputOption::VALUE_NONE,
                'Show all properties'
            )
            ->addOption(
                self::OPTION_LOCALES,
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Locales',
                ['en']
            )
            ->addOption(
                self::OPTION_ENTITY,
                null,
                InputOption::VALUE_OPTIONAL,
                'for specific entity'
            )
            ->setAliases([
                'ehdev:missingEntityLabels',
            ])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Untranslated Strings');
        $all = $input->getOption(self::OPTION_SHOW_ALL);
        $locales = $input->getOption(self::OPTION_LOCALES);

        $this->checkForActiveLanguages($locales, $io);

        $missingCount = 0;
        $entityCount = 0;

        /** @var EntityConfigModel $item */
        foreach ($this->getAllConfiguredEntities() as $item) {
            $translations = [];
            $className = $item->getClassName();

            $entity = $input->getOption(self::OPTION_ENTITY);

            if (
                ($input->getOption(self::OPTION_IGNORE_ORO) && preg_match('/^Oro[^s]/i', $className)) ||
                ($input->getOption(self::OPTION_IGNORE_EXTEND) && preg_match('/^Extend[^s]/i', $className)) ||
                ($entity !== $className && !is_null($entity))
            ) {
                continue;
            }

            $translations = $this->getEntityPropertyTranslationProvider()->getTranslations($item, $locales);
            $translations = array_filter($translations, function (PropertyTranslation $translation) use ($all) {
                return $all || !$translation->isPartialTranslatied();
            });

            $missingTranslations = array_filter($translations, function (PropertyTranslation $translation) {
                return !$translation->isPartialTranslatied();
            });

            /** @var Table $tableHelper */
            $tableHelper = new Table($output);
            $tableHelper->setHeaders(array_merge(['Property', 'Data Type'], $locales, ['transKey']));

            /** @var PropertyTranslation $translation */
            foreach ($translations as $translation) {
                $row = [
                    $translation->getPropertyName(),
                    $translation->getFieldType(),
                ];
                foreach ($locales as $locale) {
                    array_push($row, $translation->isTranslated($locale) ? Emoji::checkMarkButton() : Emoji::crossMark());
                }
                array_push($row, $translation->getTranslationKey());
                $tableHelper->addRow($row);
            }

            $io->section(sprintf('Found %s missing labels in %s', count($missingTranslations), $className));
            $tableHelper->setStyle('symfony-style-guide');
            $tableHelper->render();
            $io->newLine(2);

            if (count($missingTranslations) > 0) {
                ++$entityCount;
            }

            $missingCount += count($missingTranslations);
        }

        if (0 === $missingCount) {
            return 0;
        }

        $io->error(sprintf('%s missing Labels found for %s Entities', $missingCount, $entityCount));

        return 1;
    }

    private function checkForActiveLanguages(array $locales, SymfonyStyle $io): void
    {
        $availableLanguageCodes = [];
        /** @var Localization $item */
        foreach ($this->getLocalizationRepository()->findAll() as $item) {
            $availableLanguageCodes[] = $item->getLanguage()->getCode();
        }

        $io->note(
            sprintf(
                'Available Languages: %s',
                join(', ', $availableLanguageCodes)
            )
        );

        $notActiveLocales = array_filter($locales, function (string $locale) use ($availableLanguageCodes) {
            return !in_array($locale, $availableLanguageCodes, true);
        });

        if ($notActiveLocales) {
            $io->warning(
                sprintf(
                    'Some locales are not activated in Oro yet so that can cause to some problems. %s',
                    join(', ', $notActiveLocales)
                )
            );
        }
    }

    private function getEntityPropertyTranslationProvider(): EntityPropertyTranslationProvider
    {
        return $this->getContainer()->get('ehdev.orobasics.provider.entity_property_translation_provider');
    }

    private function getAllConfiguredEntities(): array
    {
        return $this->getEntityConfigModelRepository()
                    ->findBy([], ['className' => 'ASC']);
    }

    private function getEntityConfigModelRepository(): ObjectRepository
    {
        return $this->getContainer()
                ->get('doctrine')->getManager('config')
                ->getRepository(self::ENTITY_CLASS_NAME);
    }

    private function getLocalizationRepository(): LocalizationRepository
    {
        return $this->getContainer()
                ->get('doctrine')->getRepository(Localization::class);
    }
}
