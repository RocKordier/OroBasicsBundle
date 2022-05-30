<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Extension;

use Oro\Bundle\DataGridBundle\Exception\InvalidArgumentException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait DatagridFilterTypeExtensionTrait
{
    private function getNormalizer()
    {
        return function (Options $options, $value) {
            $ehdevOpt = $options->offsetGet('ehdev_options');

            $value = array_flip($value);

            if ($ehdevOpt && array_key_exists('filter_sort', $ehdevOpt) && is_array($ehdevOpt['filter_sort'])) {
                $filterSort = $ehdevOpt['filter_sort'];

                foreach (array_reverse($filterSort) as $filter) {
                    if (
                    defined($this->getExtendedType().'::'.$filter)
                    ) {
                        $arrayKey = null;
                        if (array_key_exists(constant($this->getExtendedType().'::'.$filter), $value)) {
                            $arrayKey = constant($this->getExtendedType().'::'.$filter);
                        } else {
                            throw new InvalidArgumentException('Filter not defined in form type');
                        }
                        $arrayValue = $value[$arrayKey];
                        unset($value[$arrayKey]);

                        $value = [$arrayKey => $arrayValue] + $value;
                    } else {
                        throw new InvalidArgumentException('Filter Type not defined');
                    }
                }
            }

            return array_flip($value);
        };
    }

    private function setEhdevOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'ehdev_options' => [],
        ]);
    }
}
