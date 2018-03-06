<?php

namespace CultuurNet\UDB3\Model\Validation\Organizer;

use CultuurNet\UDB3\Model\Validation\ValueObject\Contact\ContactPointValidator;
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
        // Note that url is NOT required, because there exist old organizers that were
        // created in the past without a url.
        // However, url is required to create new organizers. Just not when reading
        // JSON-LD of existing organizers from eg. SAPI3.
        $rules = [
            new Key('@id', new OrganizerIDValidator(), true),
            new Key('mainLanguage', new LanguageValidator(), true),
            new Key('name', new TranslatedStringValidator('name'), true),
            new Key('url', new Url(), false),
            new Key('address', new TranslatedAddressValidator(), false),
            new Key('labels', new LabelsValidator(), false),
            new Key('hiddenLabels', new LabelsValidator(), false),
            new Key('contactPoint', new ContactPointValidator(), false),
        ];

        parent::__construct($rules);
    }
}
