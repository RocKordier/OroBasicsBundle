<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Extension;

use Oro\Bundle\DataGridBundle\Exception\InvalidArgumentException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function iter\toArray;

trait DatagridFilterTypeExtensionTrait
{
    private function getNormalizer(): \Closure
    {
        return static function (Options $options, mixed $value): array {
            $ehdevOpt = $options->offsetGet('ehdev_options');
            $ehdevOpt = \is_array($ehdevOpt) ? $ehdevOpt : [];

            /** @var array<int|string, int|string> $choices */
            $choices = \is_array($value) ? array_flip(array_filter($value, self::isArrayKey(...))) : [];

            $type = current(toArray(self::getExtendedTypes()));
            $type = \is_string($type) ? $type : '';

            if (isset($ehdevOpt['filter_remove']) && \is_array($ehdevOpt['filter_remove'])) {
                foreach (array_filter($ehdevOpt['filter_remove'], \is_string(...)) as $filter) {
                    unset($choices[$filter]);

                    $constantValue = self::getFilterConstant($type, $filter);
                    if (null !== $constantValue) {
                        unset($choices[$constantValue]);
                    }
                }
            }

            if (isset($ehdevOpt['filter_sort']) && \is_array($ehdevOpt['filter_sort'])) {
                foreach (array_reverse(array_filter($ehdevOpt['filter_sort'], \is_string(...))) as $filter) {
                    $arrayKey = self::getFilterConstant($type, $filter);
                    if (null === $arrayKey) {
                        throw new InvalidArgumentException('Filter Type not defined');
                    }
                    if (!\array_key_exists($arrayKey, $choices)) {
                        throw new InvalidArgumentException('Filter not defined in form type');
                    }

                    $arrayValue = $choices[$arrayKey];
                    unset($choices[$arrayKey]);

                    $choices = [$arrayKey => $arrayValue] + $choices;
                }
            }

            return array_flip($choices);
        };
    }

    private static function getFilterConstant(string $type, string $filter): int|string|null
    {
        $constantName = $type.'::'.$filter;
        if (!\defined($constantName)) {
            return null;
        }

        $constantValue = \constant($constantName);

        return self::isArrayKey($constantValue) ? $constantValue : null;
    }

    /**
     * @phpstan-assert-if-true int|string $value
     */
    private static function isArrayKey(mixed $value): bool
    {
        return \is_int($value) || \is_string($value);
    }

    private function setEhdevOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'ehdev_options' => [],
        ]);
    }
}
