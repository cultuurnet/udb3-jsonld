<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;

interface CalendarWithOpeningHours extends Calendar
{
    /**
     * @return OpeningHours
     */
    public function getOpeningHours();
}
