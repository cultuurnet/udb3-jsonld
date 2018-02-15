<?php

namespace CultuurNet\UDB3\Model\Event;

use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Facilities;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Terms;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Theme;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Type;
use CultuurNet\UDB3\Model\ValueObject\Text\Language;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedDescription;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;

interface Event
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
     * @return Type
     */
    public function getType();

    /**
     * @return Theme|null
     */
    public function getTheme();

    /**
     * @return Facilities
     */
    public function getFacilities();

    /**
     * @return Terms
     */
    public function getTerms();
}
