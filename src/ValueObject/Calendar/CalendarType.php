<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use TwoDotsTwice\ValueObject\String\Enum;

/**
 * @method static CalendarType single()
 * @method static CalendarType multiple()
 * @method static CalendarType periodic()
 * @method static CalendarType permanent()
 */
class CalendarType extends Enum
{
    /**
     * @inheritdoc
     */
    protected function getAllowedValues()
    {
        return [
            'single',
            'multiple',
            'periodic',
            'permanent',
        ];
    }
}
