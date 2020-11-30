<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

class SingleSubEventCalendar implements CalendarWithDateRange, CalendarWithSubEvents
{
    /**
     * @var SubEvent
     */
    private $subEvent;

    public function __construct(SubEvent $subEvent)
    {
        $this->subEvent = $subEvent;
    }

    public function getType(): CalendarType
    {
        return CalendarType::single();
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->subEvent->getDateRange()->getFrom();
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->subEvent->getDateRange()->getTo();
    }

    public function getSubEvents(): SubEvents
    {
        return new SubEvents($this->subEvent);
    }
}
