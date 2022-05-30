<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Extension;

use Oro\Bundle\FilterBundle\Form\Type\Filter\NumberFilterType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberFilterTypeExtension extends AbstractTypeExtension
{
    use DatagridFilterTypeExtensionTrait;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->setEhdevOptions($resolver);
        $resolver->setNormalizer('operator_choices', $this->getNormalizer());
    }

    public static function getExtendedTypes(): iterable
    {
        return [NumberFilterType::class];
    }
}
