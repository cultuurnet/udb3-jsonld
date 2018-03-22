<?php

namespace CultuurNet\UDB3\Model\ValueObject\Text;

use TwoDotsTwice\ValueObject\String\Behaviour\IsNotEmpty;
use TwoDotsTwice\ValueObject\String\Behaviour\IsString;

/**
 * @todo Trim as well?
 */
class Description
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
