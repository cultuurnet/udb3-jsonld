<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use CultuurNet\UDB3\Model\ValueObject\Translation\TranslatedValueObject;

class TranslatedEventStatusReason extends TranslatedValueObject
{
    protected function getValueObjectClassName(): string
    {
        return EventStatusReason::class;
    }
}
