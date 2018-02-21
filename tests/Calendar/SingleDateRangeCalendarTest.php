<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use PHPUnit\Framework\TestCase;

class SingleDateRangeCalendarTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_return_a_calendar_type()
    {
        $startDate = \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018');
        $endDate = \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018');
        $calendar = new SingleDateRangeCalendar($startDate, $endDate);

        $this->assertEquals(CalendarType::single(), $calendar->getType());
    }

    /**
     * @test
     */
    public function it_should_return_the_injected_start_and_end_date()
    {
        $startDate = \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018');
        $endDate = \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018');
        $calendar = new SingleDateRangeCalendar($startDate, $endDate);

        $this->assertEquals($startDate, $calendar->getStartDate());
        $this->assertEquals($endDate, $calendar->getEndDate());
    }

    /**
     * @test
     */
    public function it_should_return_a_date_range_based_on_the_injected_start_and_end_date()
    {
        $startDate = \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018');
        $endDate = \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018');
        $calendar = new SingleDateRangeCalendar($startDate, $endDate);

        $expected = new DateRanges(
            new DateRange($startDate, $endDate)
        );

        $this->assertEquals($expected, $calendar->getDateRanges());
    }
}
