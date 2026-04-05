<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Form\Transformer;

use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\UserBundle\Entity\UserManager;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Bridges the gap between OroConfiguration (stores username as plain text)
 * and EntityType (works with User entity objects internally).
 *
 * transform:        string|null  →  User|null   (config → form)
 * reverseTransform: User|null    →  string|null  (form → config)
 *
 * @implements DataTransformerInterface<string|null|User, User|null>
 */
class UsernameToUserTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly UserManager $userManager,
    ) {}

    public function transform(mixed $value): ?User
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if ($value instanceof User) {
            $value = $value->getUserIdentifier();
        }

        $user = $this->userManager->findUserByUsername($value);

        return $user instanceof User ? $user : null;
    }

    public function reverseTransform(mixed $value): ?string
    {
        if (null === $value) {
            return null;
        }

        return $value->getUserIdentifier();
    }
}
