<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Extension;

use Oro\Bundle\FilterBundle\Form\Type\Filter\NumberRangeFilterType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberRangeFilterTypeExtension extends AbstractTypeExtension
{
    use DatagridFilterTypeExtensionTrait;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->setEhdevOptions($resolver);
        $resolver->setNormalizer('operator_choices', $this->getNormalizer());
    }

    public static function getExtendedTypes(): iterable
    {
        return [NumberRangeFilterType::class];
    }
}
