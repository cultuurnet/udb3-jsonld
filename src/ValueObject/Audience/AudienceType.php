<?php

namespace CultuurNet\UDB3\Model\ValueObject\Audience;

use TwoDotsTwice\ValueObject\String\Enum;

/**
 * @method static AudienceType everyone()
 * @method static AudienceType members()
 * @method static AudienceType education()
 */
class AudienceType extends Enum
{
    /**
     * @return array
     */
    public function getAllowedValues()
    {
        return [
            'everyone',
            'members',
            'education',
        ];
    }
}
