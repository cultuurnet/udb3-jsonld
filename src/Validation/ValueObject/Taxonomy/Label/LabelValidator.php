<?php

namespace CultuurNet\UDB3\Model\Validation\ValueObject\Taxonomy\Label;

use Respect\Validation\Rules\Length;
use Respect\Validation\Rules\StringType;
use Respect\Validation\Validator;

class LabelValidator extends Validator
{
    public function __construct()
    {
        $rules = [
            new StringType(),
            new Length(2, 255),
        ];

        parent::__construct($rules);
    }
}