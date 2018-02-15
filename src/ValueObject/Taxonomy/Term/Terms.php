<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term;

use TwoDotsTwice\ValueObject\Collection\Behaviour\FiltersDuplicates;
use TwoDotsTwice\ValueObject\Collection\Collection;

/**
 * @method Category getFirst()
 * @method Category getLast()
 * @method Category getByIndex($index)
 * @method Category[] toArray()
 * @method Terms with(Category $category)
 */
class Terms extends Collection
{
    use FiltersDuplicates;

    /**
     * @param Category[] ...$categories
     */
    public function __construct(Category ...$categories)
    {
        $filtered = $this->filterDuplicateValues($categories);
        parent::__construct(...$filtered);
    }
}
