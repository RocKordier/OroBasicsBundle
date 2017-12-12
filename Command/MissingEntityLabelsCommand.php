<?php
namespace EHDev\BasicsBundle\Command;

use Doctrine\ORM\Mapping\ClassMetadata;
use Oro\Bundle\EntityConfigBundle\Provider\ConfigProvider;
use Oro\Bundle\EntityConfigBundle\Entity\EntityConfigModel;

use Oro\Bundle\UIBundle\Tools\EntityLabelBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Translation\TranslatorInterface;

class MissingEntityLabelsCommand extends ContainerAwareCommand
{
    private $entityLabelBuilder;

    const NAME = 'ehdev:missingEntityLabels';
    const ENTITY_CLASS_NAME = 'Oro\Bundle\EntityConfigBundle\Entity\EntityConfigModel';

    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        $this->entityLabelBuilder = new EntityLabelBuilder();
    }

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Shows all Labels which are missing for entities')
            ->addOption(
                'ignore-oro',
                null,
                InputOption::VALUE_NONE,
                'Ignore Entities who lives in the Oro\... Namespace!'
            )
            ->addOption(
                'ignore-extend',
                null,
                InputOption::VALUE_NONE,
                'Ignore Entities who lives in the Extend\... Namespace!'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Untranslated Strings');

        /** @var EntityConfigModel $item */
        foreach ($this->getAllConfiguredEntities() as $item) {
            $untranslated = [];
            $className = $item->getClassName();
            $classMetaData = $this->getClassMetaData($className);

            if ($input->getOption('ignore-oro') && preg_match('/^Oro[^s]/i', $className)) {
                continue;
            }

            if ($input->getOption('ignore-extend') && preg_match('/^Extend[^s]/i', $className)) {
                continue;
            }

            /** Check EntityLabel */
            $transKey = EntityLabelBuilder::getEntityLabelTranslationKey($className);
            if($transKey == $this->getTranslation($transKey)) {
                $untranslated[] = ['entity_label', $transKey];
            }
            /** Check EntityPluralLabel */
            $transKey = EntityLabelBuilder::getEntityPluralLabelTranslationKey($className);
            if($transKey == $this->getTranslation($transKey)) {
                $untranslated[] = ['entity_plural_label', $transKey];
            }

            /** Check EntityProperties */
            $fieldNames = $classMetaData->getFieldNames();
            foreach ($fieldNames as $fieldName) {
                if(!$this->fieldIsTranslated($className, $fieldName)) {
                    $untranslated[] = [$fieldName, EntityLabelBuilder::getFieldLabelTranslationKey($className, $fieldName)];
                    }
            }

            if (count($untranslated) != 0) {
                $io->section($className);
                $io->table(
                    ['Property', 'transKey',],
                    $untranslated
                );
            }
        }

        return 0;
    }

    private function fieldIsTranslated(string $class, string $fieldName): bool
    {
        $transKey = EntityLabelBuilder::getFieldLabelTranslationKey($class, $fieldName);

        /** Check default label */
        if($transKey != $this->getTranslation($transKey)) {
            return true;
        }

        /** Check label from entityConfig */
        $transKey = $this->getConfigProvider()->getConfig($class, $fieldName)->get('label');
        if($transKey && $transKey != $this->getTranslation($transKey)) {
            return true;
        }

        return false;
    }

    private function getTranslation(string $trans): ?string
    {
        return $this->getTranslator()->trans($trans);
    }

    private function getTranslator(): TranslatorInterface
    {
        return $this->getContainer()->get('translator');
    }

    private function getConfigProvider(): ConfigProvider
    {
        return $this->getContainer()->get('oro_entity_config.provider.entity');
    }

    private function getClassMetaData(string $class): ClassMetadata
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata($class);
    }

    private function getAllConfiguredEntities(): array
    {
        /** seams bit hacky, but the entity is disabled in the chain configured namespaces */
        return
            $this->getContainer()
                ->get('doctrine')
                ->getManagerForClass(self::ENTITY_CLASS_NAME)
                ->getRepository(self::ENTITY_CLASS_NAME)
                ->findBy([], ['className' => 'ASC']);
    }
}
