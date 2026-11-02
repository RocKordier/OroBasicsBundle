<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Provider;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectRepository;
use EHDev\BasicsBundle\Model\Manager\PropertyTranslationManager;
use EHDev\BasicsBundle\Model\PropertyTranslation;
use Oro\Bundle\EntityBundle\Provider\VirtualFieldProviderInterface;
use Oro\Bundle\EntityConfigBundle\Config\ConfigManager;
use Oro\Bundle\EntityConfigBundle\Entity\EntityConfigModel;
use Oro\Bundle\EntityConfigBundle\Entity\FieldConfigModel;
use Oro\Bundle\EntityConfigBundle\Exception\RuntimeException;
use Oro\Bundle\UIBundle\Tools\EntityLabelBuilder;

class EntityPropertyTranslationProvider
{
    public const ENTITY_CLASS_NAME = EntityConfigModel::class;

    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly VirtualFieldProviderInterface $virtualFieldProvider,
        private readonly PropertyTranslationManager $propertyTranslationManager,
        private readonly ConfigManager $configManager,
    ) {}

    /**
     * @param list<string> $locales
     *
     * @return list<PropertyTranslation>
     */
    public function getTranslations(EntityConfigModel $configModel, array $locales): array
    {
        /** @var class-string $className */
        $className = $configModel->getClassName();
        $classMetaData = $this->getClassMetaData($className);

        $properties = [];
        $properties[] = $this->propertyTranslationManager->createPropertyTranslation(
            'entity_label',
            EntityLabelBuilder::getEntityLabelTranslationKey($className),
            'SYSTEM',
            $locales
        );
        $properties[] = $this->propertyTranslationManager->createPropertyTranslation(
            'entity_plural_label',
            EntityLabelBuilder::getEntityPluralLabelTranslationKey($className),
            'SYSTEM',
            $locales
        );

        $propertyNames = $this->getPropertyNames($classMetaData, $className);

        foreach ($propertyNames as $propertyName) {
            try {
                $propertyConfig = $this->configManager->getFieldConfig('entity', $className, $propertyName);
                $label = $propertyConfig->get('label');
            } catch (RuntimeException) {
                $label = null;
            }

            if (!\is_string($label) || '' === $label) {
                $label = EntityLabelBuilder::getFieldLabelTranslationKey($className, $propertyName);
            }

            $properties[] = $this->propertyTranslationManager->createPropertyTranslation(
                $propertyName,
                $label,
                $this->getFieldDataType($className, $propertyName),
                $locales
            );
        }

        return $properties;
    }

    /**
     * @param ClassMetadata<object> $classMetaData
     *
     * @return list<string>
     */
    private function getPropertyNames(ClassMetadata $classMetaData, string $className): array
    {
        $virtualFields = $this->virtualFieldProvider->getVirtualFields($className);

        $propertyNames = array_merge(
            $classMetaData->getFieldNames(),
            $classMetaData->getAssociationNames(),
            \is_array($virtualFields) ? $virtualFields : [],
        );

        return array_values(array_filter($propertyNames, \is_string(...)));
    }

    /**
     * @param class-string $class
     *
     * @return ClassMetadata<object>
     */
    private function getClassMetaData(string $class): ClassMetadata
    {
        $manager = $this->registry->getManagerForClass($class);
        if (null === $manager) {
            throw new \LogicException(\sprintf('No entity manager found for class %s.', $class));
        }

        return $manager->getClassMetadata($class);
    }

    /**
     * @return ObjectRepository<EntityConfigModel>
     */
    private function getEntityConfigModelRepository(): ObjectRepository
    {
        return $this->registry->getManager('config')
                ->getRepository(self::ENTITY_CLASS_NAME);
    }

    private function getFieldDataType(string $className, string $propertyName): string
    {
        $entity = $this->getEntityConfigModelRepository()->findOneBy(['className' => $className]);
        $field = $entity?->getField($propertyName);

        if ($field instanceof FieldConfigModel) {
            return $field->getType();
        }

        return 'NOT IN DATABASE';
    }
}
