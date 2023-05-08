<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Provider;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use EHDev\BasicsBundle\Model\Manager\PropertyTranslationManager;
use Oro\Bundle\EntityBundle\Provider\VirtualFieldProviderInterface;
use Oro\Bundle\EntityConfigBundle\Entity\EntityConfigModel;
use Oro\Bundle\EntityConfigBundle\Entity\FieldConfigModel;
use Oro\Bundle\UIBundle\Tools\EntityLabelBuilder;

class EntityPropertyTranslationProvider
{
    public const ENTITY_CLASS_NAME = 'Oro\Bundle\EntityConfigBundle\Entity\EntityConfigModel';

    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly VirtualFieldProviderInterface $virtualFieldProvider,
        private readonly PropertyTranslationManager $propertyTranslationManager
    ) {}

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
            $properties[] = $this->propertyTranslationManager->createPropertyTranslation(
                $propertyName,
                EntityLabelBuilder::getFieldLabelTranslationKey($className, $propertyName),
                $this->getFieldDataType($className, $propertyName),
                $locales
            );
        }

        return $properties;
    }

    private function getPropertyNames(ClassMetadata $classMetaData, string $className): array
    {
        $fieldNames = $classMetaData->getFieldNames();
        $associationNames = $classMetaData->getAssociationNames();
        $virtualFields = $this->virtualFieldProvider->getVirtualFields($className);

        return $fieldNames + $associationNames + $virtualFields;
    }

    /**
     * @param class-string $class
     */
    private function getClassMetaData(string $class): ClassMetaData
    {
        /** @var ObjectManager $manager */
        $manager = $this->registry->getManagerForClass($class);

        return $manager->getClassMetadata($class);
    }

    private function getEntityConfigModelRepository(): ObjectRepository
    {
        return $this->registry->getManager('config')
                ->getRepository(self::ENTITY_CLASS_NAME);
    }

    private function getFieldDataType(string $className, string $propertyName): string
    {
        /** @var EntityConfigModel $entity */
        $entity = $this->getEntityConfigModelRepository()->findOneBy(['className' => $className]);

        /** @var FieldConfigModel|false $field */
        $field = $entity->getField($propertyName);
        if ($field) {
            return $field->getType();
        }

        return 'NOT IN DATABASE';
    }
}
