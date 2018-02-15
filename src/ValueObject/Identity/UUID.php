<?php

namespace CultuurNet\UDB3\Model\ValueObject\Identity;

use TwoDotsTwice\ValueObject\String\Behaviour\IsString;
use TwoDotsTwice\ValueObject\String\Behaviour\MatchesUUIDPattern;

class UUID
{
    use IsString;
    use MatchesUUIDPattern;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->guardUUIDPattern($value);
        $this->setValue($value);
    }
}
