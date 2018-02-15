<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term;

use TwoDotsTwice\ValueObject\String\Behaviour\IsNotEmpty;
use TwoDotsTwice\ValueObject\String\Behaviour\IsString;

/**
 * @todo Check format using a regex?
 */
class CategoryID
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
