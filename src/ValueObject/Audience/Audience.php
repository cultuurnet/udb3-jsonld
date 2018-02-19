<?php

namespace CultuurNet\UDB3\Model\ValueObject\Audience;

use TwoDotsTwice\ValueObject\String\Enum;

/**
 * @method static Audience everyone()
 * @method static Audience members()
 * @method static Audience education()
 */
class Audience extends Enum
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
