<?php

namespace CultuurNet\UDB3\Model\ValueObject\Translation;

use TwoDotsTwice\ValueObject\Integer\Behaviour\IsInteger;

class MockValueObjectInteger
{
    use IsInteger;

    /**
     * @param int $value
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }
}
