<?php

namespace CultuurNet\UDB3\Model\Validation\ValueObject\Taxonomy\Label;

use Respect\Validation\Rules\AlwaysValid;
use Respect\Validation\Rules\Length;
use Respect\Validation\Rules\StringType;
use Respect\Validation\Rules\When;
use Respect\Validation\Validator;

class LabelValidator extends Validator
{
    public function __construct()
    {
        $rules = [
            new StringType(),
            new When(
                new StringType(),
                new Length(2, 255),
                new AlwaysValid()
            ),
        ];

        parent::__construct($rules);
    }
}