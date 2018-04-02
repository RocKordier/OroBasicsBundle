<?php
namespace EHDev\BasicsBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Oro\Bundle\FilterBundle\Form\Type\Filter\TextFilterType;

class TextFilterTypeExtension extends AbstractTypeExtension
{
    use DatagridFilterTypeExtensionTrait;

    public function configureOptions(OptionsResolver $resolver)
    {
        $this->setEhdevOptions($resolver);
        $resolver->setNormalizer('operator_choices', $this->getNormalizer());
    }

    public function getExtendedType()
    {
        return TextFilterType::class;
    }
}
