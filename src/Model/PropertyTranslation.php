<?php
namespace EHDev\BasicsBundle\Model;

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

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @param string $propertyName
     * @return PropertyTranslation
     */
    public function setPropertyName(string $propertyName): PropertyTranslation
    {
        $this->propertyName = $propertyName;
        return $this;
    }

    /**
     * @return string
     */
    public function getTranslationKey(): string
    {
        return $this->translationKey;
    }

    /**
     * @param string $translationKey
     * @return PropertyTranslation
     */
    public function setTranslationKey(string $translationKey): PropertyTranslation
    {
        $this->translationKey = $translationKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getTranslation(): string
    {
        return $this->translation;
    }

    /**
     * @param string $translation
     * @return PropertyTranslation
     */
    public function setTranslation(string $translation): PropertyTranslation
    {
        $this->translation = $translation;
        return $this;
    }

    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    /**
     * @param string $fieldType
     * @return PropertyTranslation
     */
    public function setFieldType(string $fieldType): PropertyTranslation
    {
        $this->fieldType = $fieldType;
        return $this;
    }

    public function isTranslated(): bool
    {
        return !($this->getTranslationKey() === $this->getTranslation());
    }
}
