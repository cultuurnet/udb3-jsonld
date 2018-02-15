<?php

namespace CultuurNet\UDB3\Model\ValueObject\Text;

/**
 * @method Description getTranslation(Language $language)
 */
class TranslatedDescription extends TranslatedText
{
    protected function getTextClassName()
    {
        return Description::class;
    }
}
