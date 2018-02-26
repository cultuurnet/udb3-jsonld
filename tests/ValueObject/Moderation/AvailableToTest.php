<?php

namespace CultuurNet\UDB3\Model\ValueObject\Moderation;

use CultuurNet\UDB3\Model\ValueObject\Calendar\DateRange;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;
use CultuurNet\UDB3\Model\ValueObject\Calendar\PermanentCalendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\SingleDateRangeCalendar;
use PHPUnit\Framework\TestCase;

class AvailableToTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_return_an_immutable_datetime_set_in_2100()
    {
        $expected = '2100-01-01T00:00:00+00:00';
        $actual = AvailableTo::forever()->format(\DateTime::ATOM);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_be_creatable_from_a_calendar()
    {
        $startDate = \DateTimeImmutable::createFromFormat('d/m/Y', '10/01/2018');
        $endDate = \DateTimeImmutable::createFromFormat('d/m/Y', '11/01/2018');

        $singleDateRangeCalendar = new SingleDateRangeCalendar(
            new DateRange($startDate, $endDate)
        );

        $permanentCalendar = new PermanentCalendar(new OpeningHours());

        $availableToFromSingleDateRange = AvailableTo::createFromCalendar($singleDateRangeCalendar);
        $availableToFromPermanent = AvailableTo::createFromCalendar($permanentCalendar);

        $this->assertEquals($endDate, $availableToFromSingleDateRange);
        $this->assertEquals(AvailableTo::forever(), $availableToFromPermanent);
    }
}
