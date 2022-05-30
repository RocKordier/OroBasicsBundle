<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Type;

use Oro\Bundle\ImapBundle\Entity\UserEmailOrigin;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OroUserEmailOriginSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'class' => UserEmailOrigin::class,
            ]
        );
    }

    public function getName(): string
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix(): string
    {
        return 'ehdev_oro_user_email_origin_select';
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
