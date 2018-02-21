<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;

class PeriodicCalendar implements CalendarWithDateRanges, CalendarWithOpeningHours
{
    /**
     * @var \DateTimeImmutable
     */
    private $startDate;

    /**
     * @var \DateTimeImmutable
     */
    private $endDate;

    /**
     * @var OpeningHours
     */
    private $openingHours;

    /**
     * @param \DateTimeImmutable $startDate
     * @param \DateTimeImmutable $endDate
     * @param OpeningHours $openingHours
     */
    public function __construct(
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        OpeningHours $openingHours
    ) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
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
        return $this->startDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate()
    {
        return $this->endDate;
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
