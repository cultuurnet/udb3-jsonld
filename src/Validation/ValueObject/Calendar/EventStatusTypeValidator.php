<?php

namespace CultuurNet\UDB3\Model\Validation\ValueObject\Calendar;

use CultuurNet\UDB3\Model\Validation\ValueObject\EnumValidator;
use CultuurNet\UDB3\Model\ValueObject\Calendar\EventStatusType;

class EventStatusTypeValidator extends EnumValidator
{
    protected function getAllowedValues(): array
    {
        // The JSON-LD values should be "https://schema.org/EventScheduled", but internally we just use
        // "EventScheduled".
        return array_map(
            function (string $allowedValue) {
                return 'https://schema.org/' . $allowedValue;
            },
            EventStatusType::getAllowedValues()
        );
    }
}
