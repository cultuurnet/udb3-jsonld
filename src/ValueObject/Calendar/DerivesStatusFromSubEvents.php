<?php declare(strict_types=1);

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

trait DerivesStatusFromSubEvents
{
    private function statusFromSubEvents(SubEvents $subEvents): Status
    {
        return new Status($this->statusTypeFromSubEvents($subEvents));
    }

    /**
     * @internal Use DerivesStatusFromSubEvents::statusFromSubEvents
     */
    private function statusTypeFromSubEvents(SubEvents $subEvents): StatusType
    {
        $statusTypeCounts = [];
        $statusTypeCounts[StatusType::Available()->toString()] = 0;
        $statusTypeCounts[StatusType::TemporarilyUnavailable()->toString()] = 0;
        $statusTypeCounts[StatusType::Unavailable()->toString()] = 0;

        /** @var SubEvent $subEvent */
        foreach ($subEvents as $subEvent) {
            ++$statusTypeCounts[$subEvent->getStatus()->getType()->toString()];
        }

        if ($statusTypeCounts[StatusType::Available()->toString()] > 0) {
            return StatusType::Available();
        }

        if ($statusTypeCounts[StatusType::TemporarilyUnavailable()->toString()] > 0) {
            return StatusType::TemporarilyUnavailable();
        }

        if ($statusTypeCounts[StatusType::Unavailable()->toString()] > 0) {
            return StatusType::Unavailable();
        }

        // This is added to make sure a return statement is present.
        // But this line can never be reached because of the required StatusType for a SubEvent.
        return StatusType::Available();
    }
}
