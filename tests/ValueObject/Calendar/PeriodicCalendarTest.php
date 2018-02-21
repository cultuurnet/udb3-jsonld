<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;
use PHPUnit\Framework\TestCase;

class PeriodicCalendarTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_return_a_calendar_type()
    {
        $startDate = \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018');
        $endDate = \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018');
        $openingHours = new OpeningHours();
        $calendar = new PeriodicCalendar($startDate, $endDate, $openingHours);

        $this->assertEquals(CalendarType::periodic(), $calendar->getType());
    }

    /**
     * @test
     */
    public function it_should_return_the_injected_start_and_end_date()
    {
        $startDate = \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018');
        $endDate = \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018');
        $openingHours = new OpeningHours();
        $calendar = new PeriodicCalendar($startDate, $endDate, $openingHours);

        $this->assertEquals($startDate, $calendar->getStartDate());
        $this->assertEquals($endDate, $calendar->getEndDate());
    }

    /**
     * @test
     */
    public function it_should_return_an_empty_date_ranges_list()
    {
        $startDate = \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018');
        $endDate = \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018');
        $openingHours = new OpeningHours();
        $calendar = new PeriodicCalendar($startDate, $endDate, $openingHours);

        $expected = new DateRanges();

        $this->assertEquals($expected, $calendar->getDateRanges());
    }

    /**
     * @test
     */
    public function it_should_return_the_injected_opening_hours()
    {
        $startDate = \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018');
        $endDate = \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018');
        $openingHours = new OpeningHours();
        $calendar = new PeriodicCalendar($startDate, $endDate, $openingHours);

        $this->assertEquals($openingHours, $calendar->getOpeningHours());
    }
}
