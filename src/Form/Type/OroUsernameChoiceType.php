<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Type;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\EntityConfigBundle\Form\Type\ChoiceType;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OroUsernameChoiceType extends AbstractType
{
    public function __construct(
        private readonly DoctrineHelper $helper
    ) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('choices', $this->getUsername());
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    private function getUsername(): iterable
    {
        $qb = $this->helper
            ->getEntityRepository(User::class)
            ->createQueryBuilder('u');

        $qb->select('u.username');
        $qb->orderBy('u.username');

        /** @var array $row */
        foreach ($qb->getQuery()->getArrayResult() as $row) {
            yield $row['username'] => $row['username'];
        }
    }
}
