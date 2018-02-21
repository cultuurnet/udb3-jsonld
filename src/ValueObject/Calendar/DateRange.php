<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use CultuurNet\UDB3\Model\ValueObject\DateTimeImmutableRange;

class DateRange extends DateTimeImmutableRange
{
    /**
     * @param \DateTimeImmutable $from
     * @param \DateTimeImmutable $to
     */
    public function __construct(\DateTimeImmutable $from, \DateTimeImmutable $to)
    {
        // Override the constructor to make both from and to required.
        parent::__construct($from, $to);
    }
}
