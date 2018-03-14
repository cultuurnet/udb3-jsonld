<?php

namespace CultuurNet\UDB3\Model\Validation\Event;

use CultuurNet\UDB3\Model\Validation\Offer\OfferValidator;
use CultuurNet\UDB3\Model\Validation\Place\PlaceReferenceValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Audience\AudienceTypeValidator;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarType;
use Respect\Validation\Rules\Key;
use Respect\Validation\Rules\KeyNested;

class EventValidator extends OfferValidator
{
    public function __construct()
    {
        $rules = [
            new Key('location', new PlaceReferenceValidator(), true),
            new KeyNested('audience.audienceType', new AudienceTypeValidator(), false),
        ];

        parent::__construct($rules);
    }

    /**
     * @inheritdoc
     */
    protected function getIDValidator()
    {
        return new EventIDValidator();
    }

    /**
     * @return string[]
     */
    protected function getAllowedCalendarTypes()
    {
        return CalendarType::getAllowedValues();
    }
}
