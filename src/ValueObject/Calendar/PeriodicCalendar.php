<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;

class PeriodicCalendar implements CalendarWithDateRanges, CalendarWithOpeningHours
{
    /**
     * @var DateRange
     */
    private $dateRange;

    /**
     * @var OpeningHours
     */
    private $openingHours;

    /**
     * @param DateRange $dateRange
     * @param OpeningHours $openingHours
     */
    public function __construct(
        DateRange $dateRange,
        OpeningHours $openingHours
    ) {
        $this->dateRange = $dateRange;
        $this->openingHours = $openingHours;
    }

    /**
     * @return CalendarType
     */
    public function getType()
    {
        return CalendarType::periodic();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate()
    {
        return $this->dateRange->getFrom();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate()
    {
        return $this->dateRange->getTo();
    }

    /**
     * @return DateRanges
     */
    public function getDateRanges()
    {
        return new DateRanges();
    }

    /**
     * @return OpeningHours
     */
    public function getOpeningHours()
    {
        return $this->openingHours;
    }
}
