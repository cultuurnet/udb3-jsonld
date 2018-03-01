<?php

namespace CultuurNet\UDB3\Model\Validation\Organizer;

use CultuurNet\UDB3\Model\Validation\ValueObject\Text\TranslatedTitleValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Translation\LanguageValidator;
use Respect\Validation\Rules\Key;
use Respect\Validation\Rules\Url;
use Respect\Validation\Validator;

class OrganizerValidator extends Validator
{
    public function __construct()
    {
        $rules = [
            new Key('@id', new OrganizerIDValidator(), true),
            new Key('mainLanguage', new LanguageValidator(), true),
            new Key('name', new TranslatedTitleValidator(), true),
            new Key('url', new Url(), true),
        ];

        parent::__construct($rules);
    }
}
