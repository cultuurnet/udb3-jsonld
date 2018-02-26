<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

interface CalendarWithSubEvents
{
    /**
     * @return DateRanges
     */
    public function getSubEvents();
}
