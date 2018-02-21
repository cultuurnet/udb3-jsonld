<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

class MultipleDateRangesCalendar implements CalendarWithDateRanges
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
     * @param DateRanges $dateRanges
     */
    public function __construct(
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        DateRanges $dateRanges
    ) {
        if ($dateRanges->getLength() < 2) {
            throw new \InvalidArgumentException('Multiple date ranges calendar requires at least 2 date ranges.');
        }

        $this->startDate = $startDate;
        $this->endDate = $endDate;
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
