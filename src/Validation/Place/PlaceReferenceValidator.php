<?php

namespace CultuurNet\UDB3\Model\Place;

use CultuurNet\UDB3\Model\Validation\Place\PlaceIDValidator;
use Respect\Validation\Rules\Key;
use Respect\Validation\Validator;

class PlaceReferenceValidator extends Validator
{
    public function __construct()
    {
        // @todo Either @id or address (dummy locations) are required. Use AnyOf() with some kind of AddressValidator.
        $rules = [
            (new Key('@id', new PlaceIDValidator(), true))
                ->setName('location @id'),
        ];

        parent::__construct($rules);
    }
}
