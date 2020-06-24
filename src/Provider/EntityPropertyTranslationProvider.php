<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Provider;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use EHDev\BasicsBundle\Model\Manager\PropertyTranslationManager;
use Oro\Bundle\EntityBundle\Provider\ConfigVirtualFieldProvider;
use Oro\Bundle\EntityConfigBundle\Entity\EntityConfigModel;
use Oro\Bundle\UIBundle\Tools\EntityLabelBuilder;

class EntityPropertyTranslationProvider
{
    const ENTITY_CLASS_NAME = 'Oro\Bundle\EntityConfigBundle\Entity\EntityConfigModel';

    private $registry;
    private $virtualFieldProvider;
    private $propertyTranslationManager;

    public function __construct(
        Registry $registry,
        ConfigVirtualFieldProvider $virtualFieldProvider,
        PropertyTranslationManager $propertyTranslationManager
    ) {
        $this->registry = $registry;
        $this->virtualFieldProvider = $virtualFieldProvider;
        $this->propertyTranslationManager = $propertyTranslationManager;
    }

    public function getTranslations(EntityConfigModel $configModel, array $locales): array
    {
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

    private function getPropertyNames(ClassMetadata $classMetaData, $className): array
    {
        $fieldNames = $classMetaData->getFieldNames();
        $associationNames = $classMetaData->getAssociationNames();
        $virtualFields = $this->virtualFieldProvider->getVirtualFields($className);

        return $fieldNames + $associationNames + $virtualFields;
    }

    private function getClassMetaData(string $class): ClassMetaData
    {
        return $this->registry->getEntityManager()->getClassMetadata($class);
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

        if ($field = $entity->getField($propertyName)) {
            return $field->getType();
        }

        return 'NOT IN DATABASE';
    }
}
