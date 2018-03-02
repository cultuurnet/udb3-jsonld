<?php

namespace CultuurNet\UDB3\Model\Validation\Offer;

use CultuurNet\UDB3\Model\Validation\ValueObject\Calendar\MultipleDateRangeCalendarValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Calendar\OpeningHours\CategoriesValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Calendar\PeriodicCalendarValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Calendar\PermanentCalendarValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Calendar\SingleDateRangeCalendarValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\ConfigurableEnumValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Taxonomy\Label\LabelsValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Text\TranslatedTitleValidator;
use CultuurNet\UDB3\Model\Validation\ValueObject\Translation\LanguageValidator;
use Respect\Validation\Rules\Key;
use Respect\Validation\Validator;

abstract class OfferValidator extends Validator
{
    public function __construct($rules)
    {
        $mandatoryRules = [
            new Key('@id', $this->getIDValidator(), true),
            new Key('mainLanguage', new LanguageValidator(), true),
            new Key('name', new TranslatedTitleValidator(), true),
            new Key('terms', new CategoriesValidator(1), true),
        ];

        $calendarRules = $this->getCalendarRules();

        $optionalRules = [
            new Key('labels', new LabelsValidator(), false),
            new Key('hiddenLabels', new LabelsValidator(), false),
        ];

        $allRules = array_merge(
            $mandatoryRules,
            $calendarRules,
            $rules,
            $optionalRules
        );

        parent::__construct($allRules);
    }

    /**
     * @return Validator
     */
    abstract protected function getIDValidator();

    /**
     * @return string[]
     */
    abstract protected function getAllowedCalendarTypes();

    /**
     * @return Validator[]
     */
    private function getCalendarRules()
    {
        $allowedTypes = $this->getAllowedCalendarTypes();

        $availableRules = [
            'single' => new SingleDateRangeCalendarValidator(),
            'multiple' => new MultipleDateRangeCalendarValidator(),
            'periodic' => new PeriodicCalendarValidator(),
            'permanent' => new PermanentCalendarValidator(),
        ];

        $rules = array_filter(
            $availableRules,
            function ($type) use ($allowedTypes) {
                return in_array($type, $allowedTypes);
            },
            ARRAY_FILTER_USE_KEY
        );

        array_unshift(
            $rules,
            new Key('calendarType', new ConfigurableEnumValidator($allowedTypes), true)
        );

        return $rules;
    }
}
