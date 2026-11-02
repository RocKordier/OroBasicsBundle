<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Command;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use EHDev\BasicsBundle\Model\PropertyTranslation;
use EHDev\BasicsBundle\Provider\EntityPropertyTranslationProvider;
use Oro\Bundle\EntityConfigBundle\Entity\EntityConfigModel;
use Oro\Bundle\LocaleBundle\Entity\Localization;
use Oro\Bundle\LocaleBundle\Entity\Repository\LocalizationRepository;
use Spatie\Emoji\Emoji;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('ehdev:missing-entity-labels', 'Shows all Labels which are missing for entities', ['ehdev:missingEntityLabels'])]
class MissingEntityLabelsCommand extends Command
{
    public const ENTITY_CLASS_NAME = EntityConfigModel::class;
    public const OPTION_IGNORE_ORO = 'ignore-oro';
    public const OPTION_IGNORE_EXTEND = 'ignore-extend';
    public const OPTION_LOCALES = 'for-locale';
    public const OPTION_ENTITY = 'entity';
    public const OPTION_SHOW_ALL = 'all';

    public function __construct(
        private readonly EntityPropertyTranslationProvider $entityPropertyTranslationProvider,
        private readonly ManagerRegistry $registry,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Untranslated Strings');
        $all = $input->getOption(self::OPTION_SHOW_ALL);
        $locales = $this->getLocales($input);

        $this->checkForActiveLanguages($locales, $io);

        $missingCount = 0;
        $entityCount = 0;

        foreach ($this->getAllConfiguredEntities() as $item) {
            $className = $item->getClassName();

            $entity = $input->getOption(self::OPTION_ENTITY);

            if (
                ($input->getOption(self::OPTION_IGNORE_ORO) && preg_match('/^Oro[^s]/i', $className))
                || ($input->getOption(self::OPTION_IGNORE_EXTEND) && preg_match('/^Extend[^s]/i', $className))
                || ($entity !== $className && null !== $entity)
            ) {
                continue;
            }

            $translations = $this->entityPropertyTranslationProvider->getTranslations($item, $locales);
            $translations = array_filter(
                $translations,
                static fn (PropertyTranslation $translation): bool => $all || !$translation->isPartialTranslatied(),
            );

            $missingTranslations = array_filter(
                $translations,
                static fn (PropertyTranslation $translation): bool => !$translation->isPartialTranslatied(),
            );

            if (\count($missingTranslations) > 0) {
                $tableHelper = new Table($output);
                $tableHelper->setHeaders(array_merge(['Property', 'Data Type'], $locales, ['transKey']));

                foreach ($translations as $translation) {
                    $row = [
                        $translation->getPropertyName(),
                        $translation->getFieldType(),
                    ];
                    foreach ($locales as $locale) {
                        $row[] = $translation->isTranslated($locale) ? Emoji::checkMarkButton() : Emoji::crossMark();
                    }
                    $row[] = $translation->getTranslationKey();
                    $tableHelper->addRow($row);
                }

                $io->section(\sprintf('Found %s missing labels in %s', \count($missingTranslations), $className));
                $tableHelper->setStyle('symfony-style-guide');
                $tableHelper->render();
                $io->newLine(2);

                ++$entityCount;
            }

            $missingCount += \count($missingTranslations);
        }

        if (0 === $missingCount) {
            $io->success('Everything is translated!');

            return 0;
        }

        $io->error(\sprintf('%s missing Labels found for %s Entities', $missingCount, $entityCount));

        return 1;
    }

    /**
     * @return list<string>
     */
    private function getLocales(InputInterface $input): array
    {
        $locales = $input->getOption(self::OPTION_LOCALES);
        if (!\is_array($locales)) {
            return [];
        }

        return array_values(array_map(strval(...), array_filter($locales, \is_scalar(...))));
    }

    /**
     * @param list<string> $locales
     */
    private function checkForActiveLanguages(array $locales, SymfonyStyle $io): void
    {
        $availableLanguageCodes = [];
        /** @var ObjectManager $manager */
        $manager = $this->registry->getManagerForClass(Localization::class);
        /** @var LocalizationRepository $repository */
        $repository = $manager->getRepository(Localization::class);
        /** @var Localization $item */
        foreach ($repository->findAll() as $item) {
            $availableLanguageCodes[] = $item->getLanguage()->getCode();
        }

        $io->note(
            \sprintf(
                'Available Languages: %s',
                implode(', ', $availableLanguageCodes)
            )
        );

        $notActiveLocales = array_filter(
            $locales,
            static fn (string $locale): bool => !\in_array($locale, $availableLanguageCodes, true),
        );

        if ($notActiveLocales) {
            $io->warning(
                \sprintf(
                    'Some locales are not activated in Oro yet so that can cause to some problems. %s',
                    implode(', ', $notActiveLocales)
                )
            );
        }
    }

    /**
     * @return list<EntityConfigModel>
     */
    private function getAllConfiguredEntities(): array
    {
        return array_values($this->getEntityConfigModelRepository()->findBy([], ['className' => 'ASC']));
    }

    /**
     * @return ObjectRepository<EntityConfigModel>
     */
    private function getEntityConfigModelRepository(): ObjectRepository
    {
        return $this->registry
                    ->getManager('config')
                    ->getRepository(self::ENTITY_CLASS_NAME);
    }
}
