<?php

namespace CultuurNet\UDB3\Model\ValueObject\MediaObject;

use TwoDotsTwice\ValueObject\String\Behaviour\IsNotEmpty;
use TwoDotsTwice\ValueObject\String\Behaviour\IsString;
use TwoDotsTwice\ValueObject\String\Behaviour\Trims;

class CopyrightHolder
{
    use IsString;
    use IsNotEmpty;
    use Trims;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $value = $this->trim($value);
        $this->guardNotEmpty($value);
        $this->setValue($value);
    }
}
