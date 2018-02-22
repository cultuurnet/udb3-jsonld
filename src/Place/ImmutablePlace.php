<?php

namespace CultuurNet\UDB3\Model\Event;

use CultuurNet\Geocoding\Coordinate\Coordinates;
use CultuurNet\UDB3\Model\Offer\ImmutableOffer;
use CultuurNet\UDB3\Model\Place\Place;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarWithOpeningHours;
use CultuurNet\UDB3\Model\ValueObject\Geography\TranslatedAddress;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;

class ImmutablePlace extends ImmutableOffer implements Place
{
    /**
     * @var CalendarWithOpeningHours
     */
    private $calendar;

    /**
     * @var TranslatedAddress
     */
    private $address;

    /**
     * @var Coordinates|null
     */
    private $coordinates;

    /**
     * @param UUID $id
     * @param Language $mainLanguage
     * @param TranslatedTitle $title
     * @param CalendarWithOpeningHours $calendar
     * @param TranslatedAddress $address
     * @param Categories $categories
     */
    public function __construct(
        UUID $id,
        Language $mainLanguage,
        TranslatedTitle $title,
        CalendarWithOpeningHours $calendar,
        TranslatedAddress $address,
        Categories $categories
    ) {
        parent::__construct($id, $mainLanguage, $title, $categories);
        $this->calendar = $calendar;
        $this->address = $address;
    }

    /**
     * @inheritdoc
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param CalendarWithOpeningHours $calendar
     * @return ImmutablePlace
     */
    public function withCalendar(CalendarWithOpeningHours $calendar)
    {
        $c = clone $this;
        $c->calendar = $calendar;
        return $c;
    }

    /**
     * @return TranslatedAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param TranslatedAddress $address
     * @return ImmutablePlace
     */
    public function withAddress(TranslatedAddress $address)
    {
        $c = clone $this;
        $c->address = $address;
        return $c;
    }

    /**
     * @return Coordinates|null
     */
    public function getGeoCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @param Coordinates $coordinates
     * @return ImmutablePlace
     */
    public function withGeoCoordinates(Coordinates $coordinates)
    {
        $c = clone $this;
        $c->coordinates = $coordinates;
        return $c;
    }

    /**
     * @return ImmutablePlace
     */
    public function withoutGeoCoordinates()
    {
        $c = clone $this;
        $c->coordinates = null;
        return $c;
    }
}
