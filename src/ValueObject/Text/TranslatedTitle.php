<?php

namespace CultuurNet\UDB3\Model\ValueObject\Text;

/**
 * @method Title getTranslation(Language $language)
 */
class TranslatedTitle extends TranslatedText
{
    protected function getTextClassName()
    {
        return Title::class;
    }
}
