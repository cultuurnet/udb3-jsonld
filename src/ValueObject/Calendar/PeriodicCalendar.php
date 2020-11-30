<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;

class PeriodicCalendar implements CalendarWithDateRange, CalendarWithOpeningHours
{
    /**
     * @var DateRange
     */
    private $dateRange;

    /**
     * @var OpeningHours
     */
    private $openingHours;

    public function __construct(
        DateRange $dateRange,
        OpeningHours $openingHours
    ) {
        $this->dateRange = $dateRange;
        $this->openingHours = $openingHours;
    }

    public function getType(): CalendarType
    {
        return CalendarType::periodic();
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->dateRange->getFrom();
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->dateRange->getTo();
    }

    public function getOpeningHours(): OpeningHours
    {
        return $this->openingHours;
    }
}
