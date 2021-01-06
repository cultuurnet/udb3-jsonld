<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

class SingleSubEventCalendar implements CalendarWithDateRange, CalendarWithSubEvents
{
    use DeriveFromSubEvents;

    /**
     * @var SubEvent
     */
    private $subEvent;

    /**
     * @var Status
     */
    private $status;

    public function __construct(SubEvent $subEvent)
    {
        $this->subEvent = $subEvent;
        $this->status = $this->statusFromSubEvents(new SubEvents($subEvent));
    }

    public function withStatus(Status $status): Calendar
    {
        $clone = clone $this;
        $clone->status = $status;
        return $clone;
    }

    public function getType(): CalendarType
    {
        return CalendarType::single();
    }

    public function getStatus(): Status
    {
        return $this->status;
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
