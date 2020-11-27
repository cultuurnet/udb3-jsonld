<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use PHPUnit\Framework\TestCase;

class EventStatusTypeTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_have_exactly_three_values(): void
    {
        $scheduled = StatusType::EventScheduled();
        $cancelled = StatusType::EventCancelled();
        $postponed = StatusType::EventPostponed();

        $this->assertEquals('EventScheduled', $scheduled->toString());
        $this->assertEquals('EventCancelled', $cancelled->toString());
        $this->assertEquals('EventPostponed', $postponed->toString());
    }
}
