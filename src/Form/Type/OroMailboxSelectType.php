<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Type;

use Oro\Bundle\EmailBundle\Entity\Mailbox;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OroMailboxSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'class' => Mailbox::class,
                'required' => false,
                'choice_value' => function (Mailbox $mailbox = null) {
                    return $mailbox?->getId();
                },
            ]
        );
    }

    public function getName(): string
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix(): string
    {
        return 'ehdev_oro_mailbox_select';
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
