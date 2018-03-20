<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use TwoDotsTwice\ValueObject\Collection\Behaviour\IsNotEmpty;
use TwoDotsTwice\ValueObject\Collection\Collection;

class DateRanges extends Collection
{
    use IsNotEmpty;

    /**
     * @var \DateTimeImmutable
     */
    private $startDate;

    /**
     * @var \DateTimeImmutable
     */
    private $endDate;

    /**
     * @param DateRange[] ...$dateRanges
     */
    public function __construct(DateRange ...$dateRanges)
    {
        $this->guardNotEmpty($dateRanges);

        usort(
            $dateRanges,
            function (DateRange $a, DateRange $b) {
                return $a->compare($b);
            }
        );

        parent::__construct(...$dateRanges);

        if (count($dateRanges) > 0) {
            $this->startDate = $this->getFirst()->getFrom();
            $this->endDate = $this->getLast()->getTo();
        }
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
}
