<?php

namespace CultuurNet\UDB3\Model\Serializer\Event;

use CultuurNet\UDB3\Model\Event\ImmutableEvent;
use CultuurNet\UDB3\Model\Place\ImmutablePlace;
use CultuurNet\UDB3\Model\Place\PlaceReference;
use CultuurNet\UDB3\Model\ValueObject\Calendar\DateRange;
use CultuurNet\UDB3\Model\ValueObject\Calendar\DateRanges;
use CultuurNet\UDB3\Model\ValueObject\Calendar\MultipleDateRangesCalendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Day;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Days;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Hour;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Minute;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHour;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Time;
use CultuurNet\UDB3\Model\ValueObject\Calendar\PeriodicCalendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\PermanentCalendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\SingleDateRangeCalendar;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Category;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryID;
use CultuurNet\UDB3\Model\ValueObject\Text\Title;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\UnsupportedException;

class EventDenormalizerTest extends TestCase
{
    /**
     * @var EventDenormalizer
     */
    private $denormalizer;

    public function setUp()
    {
        $this->denormalizer = new EventDenormalizer();
    }

    /**
     * @test
     */
    public function it_should_denormalize_event_data_with_only_the_required_properties()
    {
        $eventData = [
            '@id' => 'https://io.uitdatabank.be/event/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Event',
            '@context' => '/contexts/event',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'location' => [
                '@id' => 'https://io.uitdatabank.be/place/dbe91250-4e4b-495c-b692-3da9563b0d52',
            ],
            'calendarType' => 'permanent',
            'terms' => [
                [
                    'id' => '0.50.1.0.1',
                ]
            ],
        ];

        $expected = new ImmutableEvent(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ),
            new PermanentCalendar(new OpeningHours()),
            PlaceReference::createWithPlaceId(new UUID('dbe91250-4e4b-495c-b692-3da9563b0d52')),
            new Categories(
                new Category(
                    new CategoryID('0.50.1.0.1')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($eventData, ImmutableEvent::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_event_data_with_title_translations()
    {
        $eventData = [
            '@id' => 'https://io.uitdatabank.be/event/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Event',
            '@context' => '/contexts/event',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
                'en' => 'Example title',
                'fr' => 'Titre de l\'exemple',
            ],
            'location' => [
                '@id' => 'https://io.uitdatabank.be/place/dbe91250-4e4b-495c-b692-3da9563b0d52',
            ],
            'calendarType' => 'permanent',
            'terms' => [
                [
                    'id' => '0.50.1.0.1',
                ]
            ],
        ];

        $expected = new ImmutableEvent(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            (new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ))
                ->withTranslation(new Language('en'), new Title('Example title'))
                ->withTranslation(new Language('fr'), new Title('Titre de l\'exemple')),
            new PermanentCalendar(new OpeningHours()),
            PlaceReference::createWithPlaceId(new UUID('dbe91250-4e4b-495c-b692-3da9563b0d52')),
            new Categories(
                new Category(
                    new CategoryID('0.50.1.0.1')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($eventData, ImmutableEvent::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_event_data_with_a_single_date_range_calendar()
    {
        $eventData = [
            '@id' => 'https://io.uitdatabank.be/event/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Event',
            '@context' => '/contexts/event',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'location' => [
                '@id' => 'https://io.uitdatabank.be/place/dbe91250-4e4b-495c-b692-3da9563b0d52',
            ],
            'calendarType' => 'single',
            'startDate' => '2018-01-01T13:00:00+01:00',
            'endDate' => '2018-01-01T17:00:00+01:00',
            'subEvent' => [
                [
                    '@type' => 'Event',
                    'startDate' => '2018-01-01T13:00:00+01:00',
                    'endDate' => '2018-01-01T17:00:00+01:00',
                ],
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.1',
                ]
            ],
        ];

        $expected = new ImmutableEvent(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ),
            new SingleDateRangeCalendar(
                new DateRange(
                    \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-01T13:00:00+01:00'),
                    \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-01T17:00:00+01:00')
                )
            ),
            PlaceReference::createWithPlaceId(new UUID('dbe91250-4e4b-495c-b692-3da9563b0d52')),
            new Categories(
                new Category(
                    new CategoryID('0.50.1.0.1')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($eventData, ImmutableEvent::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_event_data_with_a_multiple_date_ranges_calendar()
    {
        $eventData = [
            '@id' => 'https://io.uitdatabank.be/event/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Event',
            '@context' => '/contexts/event',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'location' => [
                '@id' => 'https://io.uitdatabank.be/place/dbe91250-4e4b-495c-b692-3da9563b0d52',
            ],
            'calendarType' => 'multiple',
            'startDate' => '2018-01-01T13:00:00+01:00',
            'endDate' => '2018-01-10T17:00:00+01:00',
            'subEvent' => [
                [
                    '@type' => 'Event',
                    'startDate' => '2018-01-01T13:00:00+01:00',
                    'endDate' => '2018-01-01T17:00:00+01:00',
                ],
                [
                    '@type' => 'Event',
                    'startDate' => '2018-01-03T13:00:00+01:00',
                    'endDate' => '2018-01-03T17:00:00+01:00',
                ],
                [
                    '@type' => 'Event',
                    'startDate' => '2018-01-10T13:00:00+01:00',
                    'endDate' => '2018-01-10T17:00:00+01:00',
                ],
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.1',
                ]
            ],
        ];

        $expected = new ImmutableEvent(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ),
            new MultipleDateRangesCalendar(
                new DateRanges(
                    new DateRange(
                        \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-01T13:00:00+01:00'),
                        \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-01T17:00:00+01:00')
                    ),
                    new DateRange(
                        \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-03T13:00:00+01:00'),
                        \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-03T17:00:00+01:00')
                    ),
                    new DateRange(
                        \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-10T13:00:00+01:00'),
                        \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-10T17:00:00+01:00')
                    )
                )
            ),
            PlaceReference::createWithPlaceId(new UUID('dbe91250-4e4b-495c-b692-3da9563b0d52')),
            new Categories(
                new Category(
                    new CategoryID('0.50.1.0.1')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($eventData, ImmutableEvent::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_event_data_with_a_periodic_calendar_and_no_opening_hours()
    {
        $eventData = [
            '@id' => 'https://io.uitdatabank.be/event/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Event',
            '@context' => '/contexts/event',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'location' => [
                '@id' => 'https://io.uitdatabank.be/place/dbe91250-4e4b-495c-b692-3da9563b0d52',
            ],
            'calendarType' => 'periodic',
            'startDate' => '2018-01-01T13:00:00+01:00',
            'endDate' => '2018-01-10T17:00:00+01:00',
            'terms' => [
                [
                    'id' => '0.50.1.0.1',
                ]
            ],
        ];

        $expected = new ImmutableEvent(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ),
            new PeriodicCalendar(
                new DateRange(
                    \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-01T13:00:00+01:00'),
                    \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-10T17:00:00+01:00')
                ),
                new OpeningHours()
            ),
            PlaceReference::createWithPlaceId(new UUID('dbe91250-4e4b-495c-b692-3da9563b0d52')),
            new Categories(
                new Category(
                    new CategoryID('0.50.1.0.1')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($eventData, ImmutableEvent::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_event_data_with_a_periodic_calendar_and_opening_hours()
    {
        $eventData = [
            '@id' => 'https://io.uitdatabank.be/event/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Event',
            '@context' => '/contexts/event',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'location' => [
                '@id' => 'https://io.uitdatabank.be/place/dbe91250-4e4b-495c-b692-3da9563b0d52',
            ],
            'calendarType' => 'periodic',
            'startDate' => '2018-01-01T13:00:00+01:00',
            'endDate' => '2018-01-10T17:00:00+01:00',
            'openingHours' => [
                [
                    'dayOfWeek' => ['monday', 'tuesday'],
                    'opens' => '08:00',
                    'closes' => '12:00',
                ],
                [
                    'dayOfWeek' => ['monday', 'tuesday'],
                    'opens' => '13:00',
                    'closes' => '17:00',
                ],
                [
                    'dayOfWeek' => ['saturday'],
                    'opens' => '08:00',
                    'closes' => '17:45',
                ],
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.1',
                ]
            ],
        ];

        $expected = new ImmutableEvent(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ),
            new PeriodicCalendar(
                new DateRange(
                    \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-01T13:00:00+01:00'),
                    \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-10T17:00:00+01:00')
                ),
                new OpeningHours(
                    new OpeningHour(
                        new Days(new Day('monday'), new Day('tuesday')),
                        new Time(new Hour(8), new Minute(0)),
                        new Time(new Hour(12), new Minute(0))
                    ),
                    new OpeningHour(
                        new Days(new Day('monday'), new Day('tuesday')),
                        new Time(new Hour(13), new Minute(0)),
                        new Time(new Hour(17), new Minute(0))
                    ),
                    new OpeningHour(
                        new Days(new Day('saturday')),
                        new Time(new Hour(8), new Minute(0)),
                        new Time(new Hour(17), new Minute(45))
                    )
                )
            ),
            PlaceReference::createWithPlaceId(new UUID('dbe91250-4e4b-495c-b692-3da9563b0d52')),
            new Categories(
                new Category(
                    new CategoryID('0.50.1.0.1')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($eventData, ImmutableEvent::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_event_data_with_a_permanent_calendar_and_opening_hours()
    {
        $eventData = [
            '@id' => 'https://io.uitdatabank.be/event/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Event',
            '@context' => '/contexts/event',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'location' => [
                '@id' => 'https://io.uitdatabank.be/place/dbe91250-4e4b-495c-b692-3da9563b0d52',
            ],
            'calendarType' => 'permanent',
            'openingHours' => [
                [
                    'dayOfWeek' => ['monday', 'tuesday'],
                    'opens' => '08:00',
                    'closes' => '12:00',
                ],
                [
                    'dayOfWeek' => ['monday', 'tuesday'],
                    'opens' => '13:00',
                    'closes' => '17:00',
                ],
                [
                    'dayOfWeek' => ['saturday'],
                    'opens' => '08:00',
                    'closes' => '17:45',
                ],
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.1',
                ]
            ],
        ];

        $expected = new ImmutableEvent(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ),
            new PermanentCalendar(
                new OpeningHours(
                    new OpeningHour(
                        new Days(new Day('monday'), new Day('tuesday')),
                        new Time(new Hour(8), new Minute(0)),
                        new Time(new Hour(12), new Minute(0))
                    ),
                    new OpeningHour(
                        new Days(new Day('monday'), new Day('tuesday')),
                        new Time(new Hour(13), new Minute(0)),
                        new Time(new Hour(17), new Minute(0))
                    ),
                    new OpeningHour(
                        new Days(new Day('saturday')),
                        new Time(new Hour(8), new Minute(0)),
                        new Time(new Hour(17), new Minute(45))
                    )
                )
            ),
            PlaceReference::createWithPlaceId(new UUID('dbe91250-4e4b-495c-b692-3da9563b0d52')),
            new Categories(
                new Category(
                    new CategoryID('0.50.1.0.1')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($eventData, ImmutableEvent::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_when_trying_to_denormalize_to_an_unsupported_class()
    {
        $this->expectException(UnsupportedException::class);
        $this->denormalizer->denormalize([], ImmutablePlace::class);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_when_trying_to_denormalize_data_that_is_not_an_array()
    {
        $this->expectException(UnsupportedException::class);
        $this->denormalizer->denormalize(new \stdClass(), ImmutableEvent::class);
    }

    /**
     * @test
     */
    public function it_should_support_denormalization_to_immutable_event()
    {
        $this->assertTrue(
            $this->denormalizer->supportsDenormalization([], ImmutableEvent::class)
        );
    }
}
