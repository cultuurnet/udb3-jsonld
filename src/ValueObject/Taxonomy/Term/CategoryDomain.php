<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term;

use TwoDotsTwice\ValueObject\String\Behaviour\IsNotEmpty;
use TwoDotsTwice\ValueObject\String\Behaviour\IsString;

/**
 * @todo Check against predefined list of possible domains?
 */
class CategoryDomain
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
