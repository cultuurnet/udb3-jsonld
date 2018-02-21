<?php

namespace CultuurNet\UDB3\Model\Event;

use CultuurNet\UDB3\Model\Offer\ImmutableOffer;
use CultuurNet\UDB3\Model\Place\Place;
use CultuurNet\UDB3\Model\ValueObject\Audience\AudienceType;
use CultuurNet\UDB3\Model\ValueObject\Calendar\Calendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarWithOpeningHours;
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
     * @param UUID $id
     * @param Language $mainLanguage
     * @param TranslatedTitle $title
     * @param CalendarWithOpeningHours $calendar
     * @param Categories $categories
     */
    public function __construct(
        UUID $id,
        Language $mainLanguage,
        TranslatedTitle $title,
        CalendarWithOpeningHours $calendar,
        Categories $categories
    ) {
        parent::__construct($id, $mainLanguage, $title, $categories);
        $this->calendar = $calendar;
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
}
