<?php

namespace CultuurNet\UDB3\Model\Validation\Event;

use CultuurNet\UDB3\Model\Place\PlaceReferenceValidator;
use CultuurNet\UDB3\Model\Validation\Offer\OfferValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Calendar\OpeningHours\CategoriesValidator;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarType;
use Respect\Validation\Rules\Key;

class EventValidator extends OfferValidator
{
    public function __construct()
    {
        $rules = [
            new Key('location', new PlaceReferenceValidator(), true),
            new Key('terms', new CategoriesValidator(1), true),
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
