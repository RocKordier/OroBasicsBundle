<?php
namespace EHDev\BasicsBundle\Form\Type;

use Oro\Bundle\ImapBundle\Entity\UserEmailOrigin;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OroUserEmailOriginSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => UserEmailOrigin::class,
            ]
        );
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'ehdev_oro_user_email_origin_select';
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
