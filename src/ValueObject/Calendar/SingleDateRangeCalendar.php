<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

class SingleDateRangeCalendar implements CalendarWithDateRange, CalendarWithSubEvents
{
    /**
     * @var SubEvent
     */
    private $subEvent;

    public function __construct(SubEvent $subEvent)
    {
        $this->subEvent = $subEvent;
    }

    /**
     * @return CalendarType
     */
    public function getType()
    {
        return CalendarType::single();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate()
    {
        return $this->subEvent->getDateRange()->getFrom();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate()
    {
        return $this->subEvent->getDateRange()->getTo();
    }

    /**
     * @return SubEvents
     */
    public function getSubEvents()
    {
        return new SubEvents($this->subEvent);
    }
}
