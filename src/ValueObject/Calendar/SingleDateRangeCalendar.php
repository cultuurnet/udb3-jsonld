<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

class SingleDateRangeCalendar implements CalendarWithDateRange
{
    /**
     * @var DateRange
     */
    private $dateRange;

    /**
     * @param DateRange $dateRange
     */
    public function __construct(DateRange $dateRange)
    {
        $this->dateRange = $dateRange;
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
    public function getSubEvents()
    {
        return new DateRanges($this->dateRange);
    }
}
