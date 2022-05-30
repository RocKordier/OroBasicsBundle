<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Extension;

use Oro\Bundle\FilterBundle\Form\Type\Filter\TextFilterType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextFilterTypeExtension extends AbstractTypeExtension
{
    use DatagridFilterTypeExtensionTrait;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->setEhdevOptions($resolver);
        $resolver->setNormalizer('operator_choices', $this->getNormalizer());
    }

    public static function getExtendedTypes(): iterable
    {
        return [TextFilterType::class];
    }
}
