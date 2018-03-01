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
        // We don't validate the coordinates because they're optional and are
        // always based on the address. So when validating incoming json-ld on
        // UDB3 we don't care about the coordinates, and when validating json-ld
        // on other systems to deserialize it to the models there's not much we
        // can do if the coordinates are invalid. The deserializer should just
        // try to convert them to the correct models, and if it fails it should
        // just set them to null.
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
