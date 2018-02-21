<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

interface CalendarWithDateRanges extends Calendar
{
    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate();

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate();

    /**
     * @return DateRanges
     */
    public function getDateRanges();
}
