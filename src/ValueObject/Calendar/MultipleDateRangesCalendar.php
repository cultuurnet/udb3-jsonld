<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

class MultipleDateRangesCalendar implements CalendarWithDateRanges
{
    /**
     * @var DateRanges
     */
    private $dateRanges;

    /**
     * @param DateRanges $dateRanges
     */
    public function __construct(DateRanges $dateRanges)
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

    /**
     * @return DateRanges
     */
    public function getDateRanges()
    {
        return $this->dateRanges;
    }
}
