<?php

namespace CultuurNet\UDB3\Model\ValueObject\Contact;

use TwoDotsTwice\ValueObject\String\Behaviour\IsString;
use TwoDotsTwice\ValueObject\String\Behaviour\MatchesRegexPattern;

class Url
{
    use IsString;
    use MatchesRegexPattern;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->guardString($value);
        $this->guardRegexPattern('/\\Ahttp[s]?:\\/\\//', $value);

        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('Given string is not a valid url.');
        }

        $this->setValue($value);
    }
}
