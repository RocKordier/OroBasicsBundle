<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Migrations;

use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\EntityExtendBundle\Entity\EnumOption;
use Oro\Bundle\EntityExtendBundle\Entity\Repository\EnumOptionRepository;
use Oro\Bundle\EntityExtendBundle\Migration\Fixture\AbstractEnumFixture as AbstractFixture;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;
use Oro\Bundle\TranslationBundle\Translation\Translator;

/**
 * Extends the AbstractEnumFixture class of Oro to support adding new enum options
 * to an existing enum, e.g. in combination with the VersionedFixtureInterface.
 * Existing options are left untouched, new ones are appended after the highest priority.
 */
abstract class AbstractEnumFixture extends AbstractFixture
{
    #[\Override]
    public function load(ObjectManager $manager): void
    {
        $enumRepo = $manager->getRepository(EnumOption::class);
        if (!$enumRepo instanceof EnumOptionRepository) {
            throw new \LogicException(\sprintf('The repository of %s must be an %s.', EnumOption::class, EnumOptionRepository::class));
        }

        $enumCode = $this->getEnumCode();
        $priority = $this->getLastPriority($enumRepo, $enumCode) + 1;

        foreach ($this->getData() as $id => $name) {
            if (!\is_scalar($name) || '' === (string) $name) {
                continue;
            }
            $name = (string) $name;
            $internalId = ExtendHelper::buildEnumInternalId(\is_string($id) && '' !== $id ? $id : $name);

            if (null !== $enumRepo->getValue(ExtendHelper::buildEnumOptionId($enumCode, $internalId))) {
                continue;
            }

            $isDefault = $internalId === $this->getDefaultValue();
            $enumOption = $enumRepo->createEnumOption($enumCode, $internalId, $name, $priority++, $isDefault);
            if ($enumOption instanceof EnumOption) {
                $enumOption->setLocale(Translator::DEFAULT_LOCALE);
            }

            $manager->persist($enumOption);
        }

        $manager->flush();
    }

    /**
     * Returns the highest priority used by the options of the given enum.
     */
    private function getLastPriority(EnumOptionRepository $repo, string $enumCode): int
    {
        $priority = $repo->createQueryBuilder('e')
            ->select('MAX(e.priority)')
            ->where('e.enumCode = :enumCode')
            ->setParameter('enumCode', $enumCode)
            ->getQuery()
            ->getSingleScalarResult();

        return is_numeric($priority) ? (int) $priority : 0;
    }
}
