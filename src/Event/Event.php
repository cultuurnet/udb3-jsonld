<?php

namespace CultuurNet\UDB3\Model\Event;

use CultuurNet\UDB3\Model\Offer\Offer;
use CultuurNet\UDB3\Model\Organizer\OrganizerReference;
use CultuurNet\UDB3\Model\Place\PlaceReference;
use CultuurNet\UDB3\Model\ValueObject\Audience\AudienceType;
use CultuurNet\UDB3\Model\ValueObject\Calendar\Calendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarWithDateRange;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarWithOpeningHours;

interface Event extends Offer
{
    /**
     * @return Calendar|CalendarWithDateRange|CalendarWithOpeningHours
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

    /**
     * @return OrganizerReference|null
     */
    public function getOrganizerReference();
}
