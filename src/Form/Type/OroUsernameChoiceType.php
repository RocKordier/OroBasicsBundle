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
    /**
     * @var DoctrineHelper
     */
    private $helper;

    /**
     * OroUsernameChoiceType constructor.
     *
     * @param DoctrineHelper $helper
     */
    public function __construct(DoctrineHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('choices', $this->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * Fetches a list of usernames with ids.
     *
     * @return iterable
     */
    private function getUsername() : iterable
    {
        $qb = $this->helper
            ->getEntityRepository(User::class)
            ->createQueryBuilder('u');

        $qb->select('u.username');
        $qb->orderBy('u.username');

        foreach ($qb->getQuery()->getArrayResult() as $row) {
            yield $row['username'] => $row['username'];
        }
    }
}
