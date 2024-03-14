<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Security\Voter;

use Oro\Bundle\UIBundle\Provider\WidgetContextProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class IsWidgetFlow implements VoterInterface
{
    public const VOTER = 'EHDEV_IS_WIDGET_FLOW_VOTER';

    public function __construct(
        private readonly WidgetContextProvider $widgetContext
    ) {}

    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        if (!in_array(self::VOTER, $attributes)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if ($this->widgetContext->isActive()) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
