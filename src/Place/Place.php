<?php

namespace CultuurNet\UDB3\Model\Place;

use CultuurNet\Geocoding\Coordinate\Coordinates;
use CultuurNet\UDB3\Model\Offer\Offer;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarWithOpeningHours;
use CultuurNet\UDB3\Model\ValueObject\Geography\TranslatedAddress;

interface Place extends Offer
{
    /**
     * @return CalendarWithOpeningHours
     */
    public function getCalendar();

    /**
     * @return TranslatedAddress
     */
    public function getAddress();

    /**
     * @return Coordinates|null
     */
    public function getGeoCoordinates();
}
