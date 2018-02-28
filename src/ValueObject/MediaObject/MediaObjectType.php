<?php

namespace CultuurNet\UDB3\Model\ValueObject\MediaObject;

use TwoDotsTwice\ValueObject\String\Enum;

/**
 * @method static MediaObjectType imageObject()
 * @method static MediaObjectType mediaObject()
 */
class MediaObjectType extends Enum
{
    /**
     * @inheritdoc
     */
    public static function getAllowedValues()
    {
        return [
            'imageObject',
            'mediaObject',
        ];
    }
}
