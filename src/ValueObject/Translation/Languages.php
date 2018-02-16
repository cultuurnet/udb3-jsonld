<?php

namespace CultuurNet\UDB3\Model\ValueObject\Translation;

use TwoDotsTwice\ValueObject\Collection\Behaviour\FiltersDuplicates;
use TwoDotsTwice\ValueObject\Collection\Collection;

class Languages extends Collection
{
    use FiltersDuplicates;

    /**
     * @param Language[] ...$languages
     */
    public function __construct(Language ...$languages)
    {
        $filtered = $this->filterDuplicateValues($languages);
        parent::__construct(...$filtered);
    }
}
