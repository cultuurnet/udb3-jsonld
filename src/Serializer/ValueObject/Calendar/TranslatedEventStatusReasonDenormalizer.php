<?php

namespace CultuurNet\UDB3\Model\Serializer\ValueObject\Calendar;

use CultuurNet\UDB3\Model\Serializer\ValueObject\Translation\TranslatedValueObjectDenormalizer;
use CultuurNet\UDB3\Model\ValueObject\Calendar\EventStatusReason;
use CultuurNet\UDB3\Model\ValueObject\Calendar\TranslatedEventStatusReason;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;

class TranslatedEventStatusReasonDenormalizer extends TranslatedValueObjectDenormalizer
{
    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === TranslatedEventStatusReason::class;
    }

    /**
     * @inheritdoc
     */
    protected function createTranslatedValueObject(Language $originalLanguage, $originalValue)
    {
        return new TranslatedEventStatusReason($originalLanguage, $originalValue);
    }

    /**
     * @inheritdoc
     */
    protected function createValueObject($value)
    {
        return new EventStatusReason($value);
    }
}
