<?php

namespace CultuurNet\UDB3\Model\Event;

use CultuurNet\UDB3\Model\Offer\Offer;
use CultuurNet\UDB3\Model\Place\PlaceReference;
use CultuurNet\UDB3\Model\ValueObject\Audience\AudienceType;
use CultuurNet\UDB3\Model\ValueObject\Calendar\Calendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarWithDateRanges;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarWithOpeningHours;

interface Event extends Offer
{
    /**
     * @return Calendar|CalendarWithDateRanges|CalendarWithOpeningHours
     */
    public function getCalendar();

    /**
     * @return AudienceType
     */
    public function getAudienceType();

    /**
     * @return PlaceReference
     */
    public function getPlaceReference();
}
