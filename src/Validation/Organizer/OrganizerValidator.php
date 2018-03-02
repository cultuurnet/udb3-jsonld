<?php

namespace CultuurNet\UDB3\Model\Validation\Organizer;

use CultuurNet\UDB3\Model\Validation\ValueObject\Taxonomy\Label\LabelsValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Text\TranslatedAddressValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Text\TranslatedStringValidator;
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
            new Key('name', new TranslatedStringValidator('name'), true),
            new Key('url', new Url(), true),
            new Key('address', new TranslatedAddressValidator(), false),
            new Key('labels', new LabelsValidator(), false),
            new Key('hiddenLabels', new LabelsValidator(), false),
        ];

        parent::__construct($rules);
    }
}
