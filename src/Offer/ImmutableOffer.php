<?php

namespace CultuurNet\UDB3\Model\Offer;

use CultuurNet\UDB3\Model\ValueObject\Audience\AgeRange;
use CultuurNet\UDB3\Model\ValueObject\Contact\BookingInfo;
use CultuurNet\UDB3\Model\ValueObject\Contact\ContactPoint;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Moderation\WorkflowStatus;
use CultuurNet\UDB3\Model\ValueObject\Price\PriceInfo;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Label\Labels;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedDescription;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Languages;

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
     * @var Labels
     */
    private $labels;

    /**
     * @var AgeRange|null
     */
    private $ageRange;

    /**
     * @var PriceInfo|null
     */
    private $priceInfo;

    /**
     * @var BookingInfo
     */
    private $bookingInfo;

    /**
     * @var ContactPoint
     */
    private $contactPoint;

    /**
     * @var WorkflowStatus
     */
    private $workflowStatus;

    /**
     * @param UUID $id
     * @param TranslatedTitle $title
     * @param Categories $categories
     */
    public function __construct(
        UUID $id,
        TranslatedTitle $title,
        Categories $categories
    ) {
        $this->id = $id;
        $this->mainLanguage = $title->getOriginalLanguage();
        $this->title = $title;
        $this->categories = $categories;

        $this->refreshLanguages($title);

        $this->labels = new Labels();
        $this->bookingInfo = new BookingInfo();
        $this->contactPoint = new ContactPoint();
        $this->workflowStatus = WorkflowStatus::draft();
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
     * @return Labels
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param Labels $labels
     * @return ImmutableOffer
     */
    public function withLabels(Labels $labels)
    {
        $c = clone $this;
        $c->labels = $labels;
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
     * @inheritdoc
     */
    public function getPriceInfo()
    {
        return $this->priceInfo;
    }

    /**
     * @param PriceInfo $priceInfo
     * @return ImmutableOffer
     */
    public function withPriceInfo(PriceInfo $priceInfo)
    {
        $c = clone $this;
        $c->priceInfo = $priceInfo;
        return $c;
    }

    /**
     * @return ImmutableOffer
     */
    public function withoutPriceInfo()
    {
        $c = clone $this;
        $c->priceInfo = null;
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

    /**
     * @return WorkflowStatus
     */
    public function getWorkflowStatus()
    {
        return $this->workflowStatus;
    }

    /**
     * @param WorkflowStatus $workflowStatus
     * @return ImmutableOffer
     */
    public function withWorkflowStatus(WorkflowStatus $workflowStatus)
    {
        $c = clone $this;
        $c->workflowStatus = $workflowStatus;
        return $c;
    }
}
