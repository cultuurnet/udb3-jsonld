<?php

namespace CultuurNet\UDB3\Model\Offer;

use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Facilities;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Terms;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Theme;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Type;
use CultuurNet\UDB3\Model\ValueObject\Text\Language;
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
     * @var Type
     */
    private $type;

    /**
     * @var Theme|null
     */
    private $theme;

    /**
     * @var Facilities
     */
    private $facilities;

    /**
     * @param UUID $id
     * @param Language $mainLanguage
     * @param TranslatedTitle $title
     * @param Type $type
     */
    public function __construct(
        UUID $id,
        Language $mainLanguage,
        TranslatedTitle $title,
        Type $type
    ) {
        $this->id = $id;
        $this->mainLanguage = $mainLanguage;
        $this->title = $title;
        $this->type = $type;

        $this->facilities = new Facilities();
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
     * @inheritdoc
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param Type $type
     * @return ImmutableOffer
     */
    public function withType(Type $type)
    {
        $c = clone $this;
        $c->type = $type;
        return $c;
    }

    /**
     * @inheritdoc
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param Theme $theme
     * @return ImmutableOffer
     */
    public function withTheme(Theme $theme)
    {
        $c = clone $this;
        $c->theme = $theme;
        return $c;
    }

    /**
     * @return ImmutableOffer
     */
    public function withoutTheme()
    {
        $c = clone $this;
        $c->theme = null;
        return $c;
    }

    /**
     * @inheritdoc
     */
    public function getFacilities()
    {
        return $this->facilities;
    }

    /**
     * @param Facilities $facilities
     * @return ImmutableOffer
     */
    public function withFacilities(Facilities $facilities)
    {
        $c = clone $this;
        $c->facilities = $facilities;
        return $c;
    }

    /**
     * @return ImmutableOffer
     */
    public function withoutFacilities()
    {
        $c = clone $this;
        $c->facilities = new Facilities();
        return $c;
    }

    /**
     * @inheritdoc
     */
    public function getTerms()
    {
        if (isset($this->theme)) {
            return new Terms($this->getType(), $this->getTheme(), ...$this->getFacilities());
        } else {
            return new Terms($this->getType(), ...$this->getFacilities());
        }
    }
}
