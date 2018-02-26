<?php
namespace EHDev\BasicsBundle\Form\Type;

use Oro\Bundle\EmailBundle\Entity\Mailbox;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OroMailboxSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => Mailbox::class,
                'required' => false,
            ]
        );
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'ehdev_oro_mailbox_select';
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
