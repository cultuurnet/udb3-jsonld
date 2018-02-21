<?php

namespace CultuurNet\UDB3\Model\Place;

use CultuurNet\UDB3\Model\Offer\Offer;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarWithOpeningHours;

interface Place extends Offer
{
    /**
     * @return CalendarWithOpeningHours
     */
    public function getCalendar();
}
