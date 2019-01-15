<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Type;

use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OroUsernameChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('class', User::class);
        $resolver->setDefault('choice_label', 'username');
        $resolver->setDefault('choice_value', 'username');
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
