<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use TwoDotsTwice\ValueObject\Collection\Collection;

class DateRanges extends Collection
{
    /**
     * @param DateRange[] ...$dateRanges
     */
    public function __construct(DateRange ...$dateRanges)
    {
        parent::__construct(...$dateRanges);
    }
}
