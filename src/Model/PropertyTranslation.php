<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Model;

use Symfony\Component\Translation\MessageCatalogueInterface;

class PropertyTranslation
{
    /** @var string */
    private $propertyName;

    /** @var string */
    private $translationKey;

    /** @var string */
    private $translation;

    /** @var string */
    private $fieldType;

    /** @var MessageCatalogueInterface[] */
    private $catalogues;

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    public function setPropertyName(string $propertyName): PropertyTranslation
    {
        $this->propertyName = $propertyName;

        return $this;
    }

    public function getTranslationKey(): string
    {
        return $this->translationKey;
    }

    public function setTranslationKey(string $translationKey): PropertyTranslation
    {
        $this->translationKey = $translationKey;

        return $this;
    }

    public function getTranslation(string $locale): string
    {
        if (array_key_exists($locale, $this->catalogues)) {
            return $this->catalogues[$locale]->get($this->translationKey);
        }

        return $this->translationKey;
    }

    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    public function setFieldType(string $fieldType): PropertyTranslation
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    public function isTranslated(string $locale): bool
    {
        if (array_key_exists($locale, $this->catalogues)) {
            return $this->catalogues[$locale]->has($this->translationKey);
        }

        return false;
    }

    public function addCatalogue(MessageCatalogueInterface $catalogue, string $locale)
    {
        $this->catalogues[$locale] = $catalogue;
    }

    public function isPartialTranslatied(): bool
    {
        foreach ($this->catalogues as $locale => $_) {
            if (!$this->isTranslated($locale)) {
                return false;
            }
        }

        return true;
    }
}
