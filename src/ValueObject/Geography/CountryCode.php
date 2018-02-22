<?php

namespace CultuurNet\UDB3\Model\ValueObject\Geography;

use TwoDotsTwice\ValueObject\String\Behaviour\IsString;
use TwoDotsTwice\ValueObject\String\Behaviour\MatchesRegexPattern;

class CountryCode
{
    use IsString;
    use MatchesRegexPattern;

    /**
     * @param string $code
     */
    public function __construct($code)
    {
        $this->guardRegexPattern('/^[A-Z]{2}$/', $code);
        $this->setValue($code);
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->toString();
    }
}