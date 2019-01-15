<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Type;

use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OroOrganizationChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('class', Organization::class);
        $resolver->setDefault('choice_label', 'name');
        $resolver->setDefault('choice_value', 'name');
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
