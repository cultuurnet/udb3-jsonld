<?php

namespace CultuurNet\UDB3\Model\Validation\ValueObject\Calendar;

use CultuurNet\UDB3\Model\Validation\ValueObject\EnumValidator;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarType;

class CalendarTypeValidator extends EnumValidator
{
    /**
     * @inheritdoc
     */
    protected function getAllowedValues()
    {
        return CalendarType::getAllowedValues();
    }
}
