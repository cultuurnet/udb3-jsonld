<?php declare(strict_types=1);

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use PHPUnit\Framework\TestCase;

class DeriveFromSubEventsTest extends TestCase
{
    use DeriveFromSubEvents;

    /**
     * @test
     * @dataProvider subEventsDataProvider
     */
    public function it_derives_status_from_sub_events(SubEvents $subEvents, Status $status): void
    {
        $this->assertEquals($status, $this->statusFromSubEvents($subEvents));
    }

    public function subEventsDataProvider(): array
    {
        return [
            'All sub events available' => [
                new SubEvents(
                    new SubEvent(
                        new DateRange(
                            \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018'),
                            \DateTimeImmutable::createFromFormat('d/m/Y', '11/12/2018')
                        ),
                        new Status(StatusType::Available())
                    ),
                    new SubEvent(
                        new DateRange(
                            \DateTimeImmutable::createFromFormat('d/m/Y', '17/12/2018'),
                            \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018')
                        ),
                        new Status(StatusType::Available())
                    )
                ),
                new Status(StatusType::Available()),
            ],
            'All sub events unavailable' => [
                new SubEvents(
                    new SubEvent(
                        new DateRange(
                            \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018'),
                            \DateTimeImmutable::createFromFormat('d/m/Y', '11/12/2018')
                        ),
                        new Status(StatusType::Unavailable())
                    ),
                    new SubEvent(
                        new DateRange(
                            \DateTimeImmutable::createFromFormat('d/m/Y', '17/12/2018'),
                            \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018')
                        ),
                        new Status(StatusType::Unavailable())
                    )
                ),
                new Status(StatusType::Unavailable()),
            ],
            'All sub events temporary unavailable' => [
                new SubEvents(
                    new SubEvent(
                        new DateRange(
                            \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018'),
                            \DateTimeImmutable::createFromFormat('d/m/Y', '11/12/2018')
                        ),
                        new Status(StatusType::TemporarilyUnavailable())
                    ),
                    new SubEvent(
                        new DateRange(
                            \DateTimeImmutable::createFromFormat('d/m/Y', '17/12/2018'),
                            \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018')
                        ),
                        new Status(StatusType::TemporarilyUnavailable())
                    )
                ),
                new Status(StatusType::TemporarilyUnavailable()),
            ],
            'Only one sub event available' => [
                new SubEvents(
                    new SubEvent(
                        new DateRange(
                            \DateTimeImmutable::createFromFormat('d/m/Y', '10/12/2018'),
                            \DateTimeImmutable::createFromFormat('d/m/Y', '11/12/2018')
                        ),
                        new Status(StatusType::Available())
                    ),
                    new SubEvent(
                        new DateRange(
                            \DateTimeImmutable::createFromFormat('d/m/Y', '17/12/2018'),
                            \DateTimeImmutable::createFromFormat('d/m/Y', '18/12/2018')
                        ),
                        new Status(StatusType::Unavailable())
                    )
                ),
                new Status(StatusType::Available()),
            ],
        ];
    }
}
