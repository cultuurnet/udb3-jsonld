<?php

namespace CultuurNet\UDB3\Model\Offer;

use CultuurNet\UDB3\Model\ValueObject\Audience\AgeRange;
use CultuurNet\UDB3\Model\ValueObject\Contact\BookingInfo;
use CultuurNet\UDB3\Model\ValueObject\Contact\ContactPoint;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedDescription;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;

interface Offer
{
    /**
     * @return UUID
     */
    public function getId();

    /**
     * @return Language
     */
    public function getMainLanguage();

    /**
     * @return TranslatedTitle
     */
    public function getTitle();

    /**
     * @return TranslatedDescription|null
     */
    public function getDescription();

    /**
     * @return Categories
     */
    public function getTerms();

    /**
     * @return AgeRange|null
     */
    public function getAgeRange();

    /**
     * @return BookingInfo
     */
    public function getBookingInfo();

    /**
     * @return ContactPoint
     */
    public function getContactPoint();
}
