<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Model\Manager;

use EHDev\BasicsBundle\Model\PropertyTranslation;
use Symfony\Component\Translation\DataCollectorTranslator;

class PropertyTranslationManager
{
    public function __construct(
        private readonly DataCollectorTranslator $translator
    ) {}

    public function createPropertyTranslation(string $propertyName, string $translationKey, string $fieldType, array $locales): PropertyTranslation
    {
        $propertyTranslation = new PropertyTranslation();
        $propertyTranslation->setPropertyName($propertyName);
        $propertyTranslation->setTranslationKey($translationKey);

        foreach ($locales as $locale) {
            $propertyTranslation->addCatalogue($this->translator->getCatalogue($locale), $locale);
        }

        $fieldTypeKey = 'oro.entity_extend.form.data_type.'.$fieldType;
        if (($trans = $this->translator->trans($fieldTypeKey)) != $fieldTypeKey) {
            $propertyTranslation->setFieldType($trans);
        } else {
            $propertyTranslation->setFieldType($fieldType);
        }

        return $propertyTranslation;
    }
}
