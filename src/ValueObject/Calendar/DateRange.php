<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use CultuurNet\UDB3\Model\ValueObject\DateTimeImmutableRange;

class DateRange extends DateTimeImmutableRange
{
    /**
     * @var Status
     */
    private $status;

    public function __construct(\DateTimeImmutable $from, \DateTimeImmutable $to, ?Status $status = null)
    {
        // Override the constructor to make both from and to required.
        parent::__construct($from, $to);
        $this->status = $status ?? new Status(StatusType::Available());
    }

    /**
     * @param DateRange $dateRange
     * @return int
     *   Negative if this date range is less than the given date range.
     *   Positive if this date range is greater than the given date range.
     *   Zero if both ranges are the same.
     */
    public function compare(DateRange $dateRange)
    {
        if ($this->getFrom() < $dateRange->getFrom()) {
            return -1;
        }

        if ($this->getFrom() > $dateRange->getFrom()) {
            return +1;
        }

        if ($this->getTo() < $dateRange->getTo()) {
            return -1;
        }

        if ($this->getTo() > $dateRange->getTo()) {
            return +1;
        }

        return 0;
    }

    public function getEventStatus(): Status
    {
        return $this->status;
    }
}
