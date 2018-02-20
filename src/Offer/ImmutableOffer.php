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

abstract class ImmutableOffer implements Offer
{
    /**
     * @var UUID
     */
    private $id;

    /**
     * @var Language
     */
    private $mainLanguage;

    /**
     * @var TranslatedTitle
     */
    private $title;

    /**
     * @var TranslatedDescription|null
     */
    private $description;

    /**
     * @var Categories
     */
    private $categories;

    /**
     * @var AgeRange|null
     */
    private $ageRange;

    /**
     * @var BookingInfo
     */
    private $bookingInfo;

    /**
     * @var ContactPoint
     */
    private $contactPoint;

    /**
     * @param UUID $id
     * @param Language $mainLanguage
     * @param TranslatedTitle $title
     * @param Categories $categories
     */
    public function __construct(
        UUID $id,
        Language $mainLanguage,
        TranslatedTitle $title,
        Categories $categories
    ) {
        $this->id = $id;
        $this->mainLanguage = $mainLanguage;
        $this->title = $title;
        $this->categories = $categories;

        $this->bookingInfo = new BookingInfo();
        $this->contactPoint = new ContactPoint();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getMainLanguage()
    {
        return $this->mainLanguage;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param TranslatedTitle $title
     * @return ImmutableOffer
     */
    public function withTitle(TranslatedTitle $title)
    {
        $c = clone $this;
        $c->title = $title;
        return $c;
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param TranslatedDescription $translatedDescription
     * @return ImmutableOffer
     */
    public function withDescription(TranslatedDescription $translatedDescription)
    {
        $c = clone $this;
        $c->description = $translatedDescription;
        return $c;
    }

    /**
     * @return ImmutableOffer
     */
    public function withoutDescription()
    {
        $c = clone $this;
        $c->description = null;
        return $c;
    }

    /**
     * @return Categories
     */
    public function getTerms()
    {
        return $this->categories;
    }

    /**
     * @param Categories $categories
     * @return ImmutableOffer
     */
    public function withTerms(Categories $categories)
    {
        $c = clone $this;
        $c->categories = $categories;
        return $c;
    }

    /**
     * @return AgeRange|null
     */
    public function getAgeRange()
    {
        return $this->ageRange;
    }

    /**
     * @param AgeRange $ageRange
     * @return ImmutableOffer
     */
    public function withAgeRange(AgeRange $ageRange)
    {
        $c = clone $this;
        $c->ageRange = $ageRange;
        return $c;
    }

    /**
     * @return ImmutableOffer
     */
    public function withoutAgeRange()
    {
        $c = clone $this;
        $c->ageRange = null;
        return $c;
    }

    /**
     * @return BookingInfo
     */
    public function getBookingInfo()
    {
        return $this->bookingInfo;
    }

    /**
     * @param BookingInfo $bookingInfo
     * @return ImmutableOffer
     */
    public function withBookingInfo(BookingInfo $bookingInfo)
    {
        $c = clone $this;
        $c->bookingInfo = $bookingInfo;
        return $c;
    }

    /**
     * @return ContactPoint
     */
    public function getContactPoint()
    {
        return $this->contactPoint;
    }

    /**
     * @param ContactPoint $contactPoint
     * @return ImmutableOffer
     */
    public function withContactPoint(ContactPoint $contactPoint)
    {
        $c = clone $this;
        $c->contactPoint = $contactPoint;
        return $c;
    }
}
