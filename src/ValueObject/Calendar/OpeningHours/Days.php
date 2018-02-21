<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours;

use TwoDotsTwice\ValueObject\Collection\Behaviour\HasUniqueValues;
use TwoDotsTwice\ValueObject\Collection\Collection;

class Days extends Collection
{
    use HasUniqueValues;

    /**
     * @param Day[] ...$days
     */
    public function __construct(Day ...$days)
    {
        $this->guardUniqueValues($days);
        parent::__construct(...$days);
    }
}
