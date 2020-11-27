<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use CultuurNet\UDB3\Model\ValueObject\String\Enum;

/**
 * @method static StatusType EventScheduled()
 * @method static StatusType EventPostponed()
 * @method static StatusType EventCancelled()
 */
class StatusType extends Enum
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
