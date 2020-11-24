<?php

namespace CultuurNet\UDB3\Model\Validation\ValueObject\Calendar;

use CultuurNet\UDB3\Model\Validation\ValueObject\EnumValidator;
use CultuurNet\UDB3\Model\ValueObject\Calendar\EventStatusType;

class EventStatusTypeValidator extends EnumValidator
{
    protected function getAllowedValues(): array
    {
        return EventStatusType::getAllowedValues();
    }
}
