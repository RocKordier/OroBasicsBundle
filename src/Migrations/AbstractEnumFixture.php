<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Migrations;

use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\EntityExtendBundle\Entity\AbstractEnumValue;
use Oro\Bundle\EntityExtendBundle\Entity\Repository\EnumValueRepository;
use Oro\Bundle\EntityExtendBundle\Migration\Fixture\AbstractEnumFixture as AbstractFixture;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;

/**
 * Extends the AbstractEnumFixture class to support
 * add new enum options.
 */
abstract class AbstractEnumFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        /** @var class-string $className */
        $className = ExtendHelper::buildEnumValueClassName($this->getEnumCode());
        /** @var EnumValueRepository $enumRepo */
        $enumRepo = $manager->getRepository($className);
        $priority = $this->getLastPriority($enumRepo);

        foreach ($this->getData() as $id => $name) {
            if (!is_string($id) || 0 === strlen($id)) {
                $id = $name;
            }
            $id = ExtendHelper::buildEnumValueId($id);

            $isDefault = $id === $this->getDefaultValue();

            /** @var AbstractEnumValue|false $enumOption */
            $enumOption = $enumRepo->find($id);
            if (!$enumOption) {
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
     */
    private function getLastPriority(EnumValueRepository $repo): int
    {
        return intval($repo->createQueryBuilder('e')
            ->select('MAX(e.priority)')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR));
    }
}
