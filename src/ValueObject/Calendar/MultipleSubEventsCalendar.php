<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

class MultipleSubEventsCalendar implements CalendarWithDateRange, CalendarWithSubEvents
{
    /**
     * @var SubEvents
     */
    private $dateRanges;

    /**
     * @param SubEvents $dateRanges
     */
    public function __construct(SubEvents $dateRanges)
    {
        if ($dateRanges->getLength() < 2) {
            throw new \InvalidArgumentException('Multiple date ranges calendar requires at least 2 date ranges.');
        }

        $this->dateRanges = $dateRanges;
    }

    /**
     * @return CalendarType
     */
    public function getType()
    {
        return CalendarType::multiple();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate()
    {
        return $this->dateRanges->getStartDate();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate()
    {
        return $this->dateRanges->getEndDate();
    }

    public function getSubEvents(): SubEvents
    {
        return $this->dateRanges;
    }
}
