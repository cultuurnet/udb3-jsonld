<?php

namespace CultuurNet\UDB3\Model\Event;

use CultuurNet\UDB3\Model\Offer\ImmutableOffer;
use CultuurNet\UDB3\Model\ValueObject\Audience\Audience;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;

class ImmutableEvent extends ImmutableOffer implements Event
{
    /**
     * @var Audience
     */
    private $audience;

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
        parent::__construct($id, $mainLanguage, $title, $categories);
        $this->audience = Audience::everyone();
    }

    /**
     * @inheritdoc
     */
    public function getAudience()
    {
        return $this->audience;
    }

    /**
     * @param Audience $audience
     * @return ImmutableEvent
     */
    public function withAudience(Audience $audience)
    {
        $c = clone $this;
        $c->audience = $audience;
        return $c;
    }
}
