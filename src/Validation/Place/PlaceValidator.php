<?php

namespace CultuurNet\UDB3\Model\Validation\Place;

use CultuurNet\UDB3\Model\Place\PlaceReferenceValidator;
use CultuurNet\UDB3\Model\Validation\Offer\OfferValidator;
use CultuurNet\UDB3\Model\Validation\Place\PlaceIDValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Calendar\OpeningHours\CategoriesValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Geography\AddressValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Text\TranslatedAddressValidator;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarType;
use Respect\Validation\Rules\Key;

class PlaceValidator extends OfferValidator
{
    public function __construct()
    {
        $rules = [
            new Key('address', new TranslatedAddressValidator(), true),
        ];

        parent::__construct($rules);
    }

    /**
     * @inheritdoc
     */
    protected function getIDValidator()
    {
        return new PlaceIDValidator();
    }

    /**
     * @return string[]
     */
    protected function getAllowedCalendarTypes()
    {
        return [
            'periodic',
            'permanent',
        ];
    }
}
