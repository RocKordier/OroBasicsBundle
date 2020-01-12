<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Migrations;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\AbstractQuery;
use Oro\Bundle\EntityExtendBundle\Entity\Repository\EnumValueRepository;
use Oro\Bundle\EntityExtendBundle\Migration\Fixture\AbstractEnumFixture as AbstractFixture;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;

/**
 * Extends the AbstractEnumFixture class to support
 * add new enum options.
 */
abstract class AbstractEnumFixture extends AbstractFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $className = ExtendHelper::buildEnumValueClassName($this->getEnumCode());
        /** @var EnumValueRepository $enumRepo */
        $enumRepo = $manager->getRepository($className);
        $priority = $this->getLastPriority($enumRepo);

        foreach ($this->getData() as $id => $name) {
            if (0 === strlen($id)) {
                $id = ExtendHelper::buildEnumValueId($name);
            }

            $isDefault = $id === $this->getDefaultValue();

            if (!$enumOption = $enumRepo->find($id)) {
                $enumOption = $enumRepo->createEnumValue($name, $priority++, $isDefault, $id);
                $manager->persist($enumOption);
            } elseif ($enumOption->getName() !== $name || $enumOption->isDefault() !== $isDefault) {
                $manager->persist($enumOption);
            }
        }

        $manager->flush();
    }

    /**
     * Returns the last used priority.
     *
     * @param EnumValueRepository $repo
     *
     * @return int
     */
    private function getLastPriority(EnumValueRepository $repo): int
    {
        return (int) $repo->createQueryBuilder('e')
            ->select('MAX(e.priority)')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }
}
