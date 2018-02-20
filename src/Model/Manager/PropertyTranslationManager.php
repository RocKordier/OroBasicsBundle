<?php
namespace EHDev\BasicsBundle\Model\Manager;

use EHDev\BasicsBundle\Model\PropertyTranslation;
use Symfony\Component\Translation\TranslatorInterface;

class PropertyTranslationManager
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function createPropertyTranslation($propertyName, $translationKey, $fieldType): PropertyTranslation
    {
        $propertyTranslation = new PropertyTranslation();
        $propertyTranslation->setPropertyName($propertyName);
        $propertyTranslation->setTranslationKey($translationKey);
        $propertyTranslation->setTranslation($this->translator->trans($translationKey));

        $fieldTypeKey = 'oro.entity_extend.form.data_type.'.$fieldType;
        if(($trans = $this->translator->trans($fieldTypeKey)) != $fieldTypeKey) {
            $propertyTranslation->setFieldType($trans);
        } else {
            $propertyTranslation->setFieldType($fieldType);
        }

        return $propertyTranslation;
    }
}
