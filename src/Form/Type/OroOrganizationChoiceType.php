<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Type;

use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OroOrganizationChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Organization::class,
            'choice_label' => 'name',
            'choice_value' => 'id',
            'empty_data' => null,
            'placeholder' => 'Choose an Organization',
        ]);
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
