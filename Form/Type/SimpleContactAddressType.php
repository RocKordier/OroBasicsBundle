<?php

namespace EHDev\Bundle\BasicsBundle\Form\Type;

use Oro\Bundle\AddressBundle\Form\Type\AddressType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;

/**
 * Class SimpleContactAddressType
 *
 * @package EHDev\Bundle\BasicsBundle\Form\Type
 */
class SimpleContactAddressType extends AddressType
{
    const LABEL_PREFIX = 'ehdev.basics.simplecontactaddress.';

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
        return 'ehdev_basics_simplecontactaddress';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'EHDev\Bundle\BasicsBundle\Entity\SimpleContactAddress',
                'intention' => 'address',
                'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
                'single_form' => true,
                'region_route' => 'oro_api_country_get_regions',
                'validation_groups' => [Constraint::DEFAULT_GROUP, self::ABSTRACT_ADDRESS_GROUP],
            )
        );
    }
}
