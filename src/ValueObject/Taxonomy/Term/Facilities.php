<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term;

use TwoDotsTwice\ValueObject\Collection\Behaviour\FiltersDuplicates;
use TwoDotsTwice\ValueObject\Collection\Collection;

/**
 * @method Facility getFirst()
 * @method Facility getLast()
 * @method Facility getByIndex($index)
 * @method Facility[] toArray()
 * @method Facilities with(Facility $facility)
 */
class Facilities extends Collection
{
    use FiltersDuplicates;

    /**
     * @param Facility[] ...$facilities
     */
    public function __construct(Facility ...$facilities)
    {
        $filtered = $this->filterDuplicateValues($facilities);
        parent::__construct(...$filtered);
    }
}
