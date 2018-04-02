<?php
namespace EHDev\BasicsBundle\Form\Extension;

use Oro\Bundle\FilterBundle\Form\Type\Filter\NumberRangeFilterType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberRangeFilterTypeExtension extends AbstractTypeExtension
{
    use DatagridFilterTypeExtensionTrait;

    public function configureOptions(OptionsResolver $resolver)
    {
        $this->setEhdevOptions($resolver);
        $resolver->setNormalizer('operator_choices', $this->getNormalizer());
    }

    public function getExtendedType()
    {
        return NumberRangeFilterType::class;
    }
}
