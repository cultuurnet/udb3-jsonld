<?php

declare(strict_types=1);

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

final class EventStatus
{
    /**
     * @var EventStatusType
     */
    private $type;

    /**
     * @var TranslatedEventStatusReason|null
     */
    private $reason;

    public function __construct(EventStatusType $type, ?TranslatedEventStatusReason $reason = null)
    {
        $this->type = $type;
        $this->reason = $reason;
    }

    public function getType(): EventStatusType
    {
        return $this->type;
    }

    public function getReason(): ?TranslatedEventStatusReason
    {
        return $this->reason;
    }
}
