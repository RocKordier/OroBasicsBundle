<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\FormBundle\Form\DataTransformer\EntitiesToIdsTransformer;

class EntitiesToCollectionTransformer extends EntitiesToIdsTransformer
{
    public function reverseTransform($value): ArrayCollection
    {
        return new ArrayCollection(parent::reverseTransform($value));
    }
}
