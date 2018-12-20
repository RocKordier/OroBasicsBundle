<?php
namespace EHDev\BasicsBundle\Model\Manager;

use EHDev\BasicsBundle\Model\PropertyTranslation;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class PropertyTranslationManager
{
    private $translator;

    public function __construct(DataCollectorTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function createPropertyTranslation(string $propertyName, string $translationKey, string $fieldType, array $locales): PropertyTranslation
    {
        $propertyTranslation = new PropertyTranslation();
        $propertyTranslation->setPropertyName($propertyName);
        $propertyTranslation->setTranslationKey($translationKey);

        foreach ($locales as $locale) {
            $this->translator->setFallbackLocales([]);
            $propertyTranslation->addCatalogue($this->translator->getCatalogue($locale), $locale);
        }

        $fieldTypeKey = 'oro.entity_extend.form.data_type.'.$fieldType;
        if(($trans = $this->translator->trans($fieldTypeKey)) != $fieldTypeKey) {
            $propertyTranslation->setFieldType($trans);
        } else {
            $propertyTranslation->setFieldType($fieldType);
        }

        return $propertyTranslation;
    }
}
