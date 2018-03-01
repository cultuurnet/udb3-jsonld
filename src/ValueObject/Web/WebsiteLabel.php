<?php

namespace CultuurNet\UDB3\Model\ValueObject\Web;

use TwoDotsTwice\ValueObject\String\Behaviour\IsNotEmpty;
use TwoDotsTwice\ValueObject\String\Behaviour\IsString;

class WebsiteLabel
{
    use IsString;
    use IsNotEmpty;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->guardNotEmpty($value);
        $this->setValue($value);
    }
}