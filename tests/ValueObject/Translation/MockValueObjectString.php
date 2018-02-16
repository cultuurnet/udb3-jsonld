<?php

namespace CultuurNet\UDB3\Model\ValueObject\Translation;

use TwoDotsTwice\ValueObject\String\Behaviour\IsString;

class MockValueObjectString
{
    use IsString;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }
}
