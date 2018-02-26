<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use PHPUnit\Framework\TestCase;

class DateRangesTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_sort_the_given_date_ranges_and_return_a_start_and_end_date()
    {
        $given = [
            ['2018-07-01', '2018-08-31'],
            ['2018-07-01', '2018-08-30'],
            ['2018-05-30', '2018-09-01'],
            ['2018-01-01', '2018-01-01'],
            ['2018-05-30', '2018-08-30'],
            ['2018-01-01', '2018-12-01'],
            ['2018-07-01', '2018-08-31'],
        ];

        $expected = [
            ['2018-01-01', '2018-01-01'],
            ['2018-01-01', '2018-12-01'],
            ['2018-05-30', '2018-08-30'],
            ['2018-05-30', '2018-09-01'],
            ['2018-07-01', '2018-08-30'],
            ['2018-07-01', '2018-08-31'],
            ['2018-07-01', '2018-08-31'],
        ];

        $mapToDateRange = function (array $range) {
            $from = $range[0];
            $to = $range[1];

            return new DateRange(
                \DateTimeImmutable::createFromFormat('Y-m-d', $from),
                \DateTimeImmutable::createFromFormat('Y-m-d', $to)
            );
        };

        $given = array_map($mapToDateRange, $given);
        $expected = array_map($mapToDateRange, $expected);

        $ranges = new DateRanges(...$given);

        $this->assertEquals($expected, $ranges->toArray());
        $this->assertEquals(7, $ranges->getLength());
        $this->assertEquals(\DateTimeImmutable::createFromFormat('Y-m-d', '2018-01-01'), $ranges->getStartDate());
        $this->assertEquals(\DateTimeImmutable::createFromFormat('Y-m-d', '2018-08-31'), $ranges->getEndDate());
    }
}
