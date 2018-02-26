<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use PHPUnit\Framework\TestCase;

class MultipleDateRangesCalendarTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_require_at_least_two_date_ranges()
    {
        $startDate = \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018');
        $endDate = \DateTimeImmutable::createFromFormat('d/m/Y', '11/12/2018');
        $dateRanges = new DateRanges(
            new DateRange($startDate, $endDate)
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Multiple date ranges calendar requires at least 2 date ranges.');

        new MultipleDateRangesCalendar($dateRanges);
    }

    /**
     * @test
     */
    public function it_should_return_a_start_and_end_date()
    {
        $startDate = \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018');
        $endDate = \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018');

        $dateRanges = new DateRanges(
            new DateRange(
                $startDate,
                \DateTimeImmutable::createFromFormat('d/m/Y', '11/12/2018')
            ),
            new DateRange(
                \DateTimeImmutable::createFromFormat('d/m/Y', '17/12/2018'),
                $endDate
            )
        );

        $calendar = new MultipleDateRangesCalendar($dateRanges);

        $this->assertEquals($startDate, $calendar->getStartDate());
        $this->assertEquals($endDate, $calendar->getEndDate());
    }

    /**
     * @test
     */
    public function it_should_return_multiple_date_ranges()
    {
        $startDate = \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018');
        $endDate = \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018');

        $dateRanges = new DateRanges(
            new DateRange(
                $startDate,
                \DateTimeImmutable::createFromFormat('d/m/Y', '11/12/2018')
            ),
            new DateRange(
                \DateTimeImmutable::createFromFormat('d/m/Y', '17/12/2018'),
                $endDate
            )
        );

        $calendar = new MultipleDateRangesCalendar($dateRanges);

        $this->assertEquals($dateRanges, $calendar->getDateRanges());
    }

    /**
     * @test
     */
    public function it_should_return_a_calendar_type()
    {
        $startDate = \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018');
        $endDate = \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018');

        $dateRanges = new DateRanges(
            new DateRange(
                $startDate,
                \DateTimeImmutable::createFromFormat('d/m/Y', '11/12/2018')
            ),
            new DateRange(
                \DateTimeImmutable::createFromFormat('d/m/Y', '17/12/2018'),
                $endDate
            )
        );

        $calendar = new MultipleDateRangesCalendar($dateRanges);

        $this->assertEquals(CalendarType::multiple(), $calendar->getType());
    }
}
