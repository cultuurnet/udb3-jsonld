<?php

namespace CultuurNet\UDB3\Model\Validation\ValueObject\Text;

use CultuurNet\UDB3\Model\Validation\ValueObject\NotEmptyStringValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Translation\LanguageValidator;
use Respect\Validation\Rules\ArrayType;
use Respect\Validation\Rules\Each;
use Respect\Validation\Rules\Length;
use Respect\Validation\Validator;

class TranslatedTitleValidator extends Validator
{
    public function __construct()
    {
        $rules = [
            new ArrayType(),
            new Each(
                // This is a quick fix to prevent '"" must not be empty" messages.
                // @see https://github.com/Respect/Validation/issues/924
                (new NotEmptyStringValidator())->setName('name value'),
                new LanguageValidator()
            ),
            new Length(1, null, true),
        ];

        parent::__construct($rules);
    }
}
