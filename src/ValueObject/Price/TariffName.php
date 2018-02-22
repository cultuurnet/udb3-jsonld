<?php

namespace CultuurNet\UDB3\Model\ValueObject\Price;

use TwoDotsTwice\ValueObject\String\Behaviour\IsNotEmpty;
use TwoDotsTwice\ValueObject\String\Behaviour\IsString;

class TariffName
{
    use IsString;
    use IsNotEmpty;

    public function __construct($value)
    {
        $this->guardNotEmpty($value);
        $this->setValue($value);
    }
}
