<?php

namespace CultuurNet\UDB3\Model\ValueObject\Text;

use TwoDotsTwice\ValueObject\String\Behaviour\IsString;
use TwoDotsTwice\ValueObject\String\Behaviour\MatchesRegexPattern;

class Language
{
    use IsString;
    use MatchesRegexPattern;

    /**
     * @param string $code
     */
    public function __construct($code)
    {
        $this->guardRegexPattern('/^[a-z]{2}$/', $code);
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
