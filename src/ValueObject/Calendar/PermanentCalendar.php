<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;

class PermanentCalendar implements CalendarWithOpeningHours
{
    /**
     * @var OpeningHours
     */
    private $openingHours;

    public function __construct(OpeningHours $openingHours)
    {
        $this->openingHours = $openingHours;
    }

    public function getType(): CalendarType
    {
        return CalendarType::permanent();
    }

    public function getOpeningHours(): OpeningHours
    {
        return $this->openingHours;
    }
}
