<?php

namespace EHDev\Bundle\BasicsBundle\Form\Type;

use Oro\Bundle\FormBundle\Form\Type\OroDateType;
use Oro\Bundle\UserBundle\Form\Type\GenderType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email as EmailAssert;

class SimpleContactType extends AbstractType
{
    const LABEL_PREFIX = 'ehdev.basics.simplecontact.';

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'ehdev_basics_simplecontact';
    }

    /**
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstName',
                TextType::class,
                [
                    'label'       => self::LABEL_PREFIX.'first_name.label',
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'middleName',
                TextType::class,
                [
                    'label'    => self::LABEL_PREFIX.'middle_name.label',
                    'required' => false,
                ]
            )->add(
                'lastName',
                TextType::class,
                [
                    'label'       => self::LABEL_PREFIX.'last_name.label',
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'gender',
                GenderType::class,
                [
                    'required' => false,
                    'label'    => self::LABEL_PREFIX.'gender.label',
                ]
            )
            ->add(
                'birthday',
                OroDateType::class,
                [
                    'required' => false,
                    'label'    => self::LABEL_PREFIX.'birthday.label',
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label'       => self::LABEL_PREFIX.'email.label',
                    'constraints' => [
                        new NotBlank(),
                        new EmailAssert(),
                    ],
                ]
            )
            ->add(
                'phone',
                TextType::class,
                [
                    'label'    => self::LABEL_PREFIX.'phone.label',
                    'required' => false,
                ]
            )
            ->add(
                'skype',
                TextType::class,
                [
                    'label'    => self::LABEL_PREFIX.'skype.label',
                    'required' => false,
                ]
            )
            ->add(
                'twitter',
                TextType::class,
                [
                    'label'    => self::LABEL_PREFIX.'twitter.label',
                    'required' => false,
                ]
            )->add(
                'facebook',
                TextType::class,
                [
                    'label'    => self::LABEL_PREFIX.'facebook.label',
                    'required' => false,
                ]
            )
//            ->add(
//                'address',
//                SimpleContactAddressType::class,
//                [
//                    'label' => self::LABEL_PREFIX.'address.label',
//                ]
//            )
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'EHDev\Bundle\BasicsBundle\Entity\SimpleContact',
            ]
        );
    }
}
