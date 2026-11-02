<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\FormBundle\Form\DataTransformer\EntitiesToIdsTransformer;

class EntitiesToCollectionTransformer extends EntitiesToIdsTransformer
{
    /**
     * @return ArrayCollection<array-key, mixed>
     */
    #[\Override]
    public function reverseTransform($value): ArrayCollection
    {
        $entities = parent::reverseTransform($value);

        return new ArrayCollection(\is_array($entities) ? $entities : []);
    }
}
