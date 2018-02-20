<?php
namespace EHDev\BasicsBundle\Command;

use Doctrine\Common\Persistence\ObjectRepository;

use EHDev\BasicsBundle\Model\PropertyTranslation;
use EHDev\BasicsBundle\Provider\EntityPropertyTranslationProvider;
use Juanparati\Emoji\Emoji;
use Oro\Bundle\EntityConfigBundle\Entity\EntityConfigModel;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MissingEntityLabelsCommand extends ContainerAwareCommand
{
    const NAME = 'ehdev:missingEntityLabels';
    const ENTITY_CLASS_NAME = 'Oro\Bundle\EntityConfigBundle\Entity\EntityConfigModel';
    const OPTION_IGNORE_ORO = 'ignore-oro';
    const OPTION_IGNORE_EXTEND = 'ignore-extend';
    const OPTION_SHOW_ALL = 'all';

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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Untranslated Strings');
        $all = $input->getOption(self::OPTION_SHOW_ALL);

        /** @var EntityConfigModel $item */
        foreach ($this->getAllConfiguredEntities() as $item) {
            $className = $item->getClassName();

            if (
                ($input->getOption(self::OPTION_IGNORE_ORO) && preg_match('/^Oro[^s]/i', $className)) ||
                ($input->getOption(self::OPTION_IGNORE_EXTEND) && preg_match('/^Extend[^s]/i', $className))
            ) {
                continue;
            }

            $translations = $this->getEntityPropertyTranslationProvider()->getTranslations($item);

            $translationsArray = [];
            /** @var PropertyTranslation $translation */
            foreach ($translations as $translation) {
                if($all || !$translation->isTranslated()){
                    $translationsArray[] = [
                        $translation->getPropertyName(),
                        $translation->getFieldType(),
                        $translation->isTranslated() ? Emoji::char('white heavy check mark') : Emoji::char('cross mark'),
                        $translation->getTranslationKey()
                    ];
                }
            }

            if (count($translationsArray) != 0) {
                $io->section($className);
                $io->table(
                    ['Property', 'Data Type', 'Status', 'transKey'],
                    $translationsArray
                );
            }
        }

        return 0;
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
        return
            $this->getContainer()
                ->get('doctrine')->getManager('config')
                ->getRepository(self::ENTITY_CLASS_NAME);
    }
}
