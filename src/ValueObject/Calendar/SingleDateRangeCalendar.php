<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

class SingleDateRangeCalendar implements CalendarWithDateRanges
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
     * @var DateRanges
     */
    private $dateRanges;

    /**
     * @param \DateTimeImmutable $startDate
     * @param \DateTimeImmutable $endDate
     */
    public function __construct(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->dateRanges = new DateRanges(
            new DateRange($startDate, $endDate)
        );
    }

    /**
     * @return CalendarType
     */
    public function getType()
    {
        return CalendarType::single();
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
        return $this->dateRanges;
    }
}
