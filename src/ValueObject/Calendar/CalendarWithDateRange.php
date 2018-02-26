<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

interface CalendarWithDateRange extends Calendar
{
    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate();

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate();
}
