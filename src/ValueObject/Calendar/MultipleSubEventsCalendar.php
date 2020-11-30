<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

class MultipleSubEventsCalendar implements CalendarWithDateRange, CalendarWithSubEvents
{
    /**
     * @var SubEvents
     */
    private $dateRanges;

    public function __construct(SubEvents $dateRanges)
    {
        if ($dateRanges->getLength() < 2) {
            throw new \InvalidArgumentException('Multiple date ranges calendar requires at least 2 date ranges.');
        }

        $this->dateRanges = $dateRanges;
    }

    public function getType(): CalendarType
    {
        return CalendarType::multiple();
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->dateRanges->getStartDate();
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->dateRanges->getEndDate();
    }

    public function getSubEvents(): SubEvents
    {
        return $this->dateRanges;
    }
}
