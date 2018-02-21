<?php

namespace CultuurNet\UDB3\Model\Event;

use CultuurNet\UDB3\Model\Offer\ImmutableOffer;
use CultuurNet\UDB3\Model\ValueObject\Audience\AudienceType;
use CultuurNet\UDB3\Model\ValueObject\Calendar\Calendar;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;

class ImmutableEvent extends ImmutableOffer implements Event
{
    /**
     * @var Calendar
     */
    private $calendar;

    /**
     * @var AudienceType
     */
    private $audience;

    /**
     * @param UUID $id
     * @param Language $mainLanguage
     * @param TranslatedTitle $title
     * @param Calendar $calendar
     * @param Categories $categories
     */
    public function __construct(
        UUID $id,
        Language $mainLanguage,
        TranslatedTitle $title,
        Calendar $calendar,
        Categories $categories
    ) {
        parent::__construct($id, $mainLanguage, $title, $categories);
        $this->calendar = $calendar;
        $this->audience = AudienceType::everyone();
    }

    /**
     * @inheritdoc
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param Calendar $calendar
     * @return ImmutableEvent
     */
    public function withCalendar(Calendar $calendar)
    {
        $c = clone $this;
        $c->calendar = $calendar;
        return $c;
    }

    /**
     * @inheritdoc
     */
    public function getAudienceType()
    {
        return $this->audience;
    }

    /**
     * @param AudienceType $audience
     * @return ImmutableEvent
     */
    public function withAudienceType(AudienceType $audience)
    {
        $c = clone $this;
        $c->audience = $audience;
        return $c;
    }
}
