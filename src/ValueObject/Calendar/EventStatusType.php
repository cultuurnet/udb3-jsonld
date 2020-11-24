<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use CultuurNet\UDB3\Model\ValueObject\String\Enum;

/**
 * @method static EventStatusType EventScheduled()
 * @method static EventStatusType EventPostponed()
 * @method static EventStatusType EventCancelled()
 */
class EventStatusType extends Enum
{
    /**
     * @inheritdoc
     */
    public static function getAllowedValues(): array
    {
        return [
            'EventScheduled',
            'EventPostponed',
            'EventCancelled',
        ];
    }
}
