<?php

namespace CultuurNet\UDB3\Model\Validation\Event;

use CultuurNet\UDB3\Model\Place\PlaceReferenceValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Calendar\CalendarTypeValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Calendar\MultipleDateRangeCalendarValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Calendar\OpeningHours\CategoriesValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Calendar\PeriodicDateRangeCalendarValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Calendar\PermanentDateRangeCalendarValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Calendar\SingleDateRangeCalendarValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Text\TranslatedTitleValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Translation\LanguageValidator;
use Respect\Validation\Rules\Key;
use Respect\Validation\Validator;

class EventValidator extends Validator
{
    public function __construct()
    {
        $rules = [
            new Key('@id', new EventIDValidator(), true),
            new Key('mainLanguage', new LanguageValidator(), true),
            new Key('name', new TranslatedTitleValidator(), true),
            new Key('calendarType', new CalendarTypeValidator(), true),
            new SingleDateRangeCalendarValidator(),
            new MultipleDateRangeCalendarValidator(),
            new PeriodicDateRangeCalendarValidator(),
            new PermanentDateRangeCalendarValidator(),
            new Key('location', new PlaceReferenceValidator(), true),
            new Key('terms', new CategoriesValidator(1), true),
        ];

        parent::__construct($rules);
    }
}
