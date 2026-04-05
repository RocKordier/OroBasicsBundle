<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Type;

use EHDev\BasicsBundle\Form\Transformer\UsernameToUserTransformer;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OroUsernameChoiceType extends AbstractType
{
    public function __construct(
        private readonly UsernameToUserTransformer $transformer,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => User::class,
            'choice_label' => 'username',
            'choice_value' => 'username',
            'empty_data' => null,
            'placeholder' => 'Please choose a User',
        ]);
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
