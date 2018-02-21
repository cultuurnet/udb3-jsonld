<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours;

use TwoDotsTwice\ValueObject\Collection\Collection;

class OpeningHours extends Collection
{
    /**
     * @param OpeningHour[] ...$openingHours
     */
    public function __construct(OpeningHour ...$openingHours)
    {
        parent::__construct(...$openingHours);
    }
}
