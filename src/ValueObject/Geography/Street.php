<?php

namespace CultuurNet\UDB3\Model\ValueObject\Geography;

use TwoDotsTwice\ValueObject\String\Behaviour\IsNotEmpty;
use TwoDotsTwice\ValueObject\String\Behaviour\IsString;

class Street
{
    use IsString;
    use IsNotEmpty;

    public function __construct($value)
    {
        $this->guardNotEmpty($value);
        $this->setValue($value);
    }
}
