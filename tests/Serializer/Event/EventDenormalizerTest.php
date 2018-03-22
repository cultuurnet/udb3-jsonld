<?php

namespace CultuurNet\UDB3\Model\Serializer\Event;

use CultuurNet\UDB3\Model\Event\ImmutableEvent;
use CultuurNet\UDB3\Model\Organizer\OrganizerReference;
use CultuurNet\UDB3\Model\Place\ImmutablePlace;
use CultuurNet\UDB3\Model\Place\PlaceReference;
use CultuurNet\UDB3\Model\ValueObject\Audience\Age;
use CultuurNet\UDB3\Model\ValueObject\Audience\AgeRange;
use CultuurNet\UDB3\Model\ValueObject\Audience\AudienceType;
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
use CultuurNet\UDB3\Model\ValueObject\Contact\BookingAvailability;
use CultuurNet\UDB3\Model\ValueObject\Contact\BookingInfo;
use CultuurNet\UDB3\Model\ValueObject\Contact\ContactPoint;
use CultuurNet\UDB3\Model\ValueObject\Contact\TelephoneNumber;
use CultuurNet\UDB3\Model\ValueObject\Contact\TelephoneNumbers;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\MediaObject\CopyrightHolder;
use CultuurNet\UDB3\Model\ValueObject\MediaObject\MediaObject;
use CultuurNet\UDB3\Model\ValueObject\MediaObject\MediaObjectReference;
use CultuurNet\UDB3\Model\ValueObject\MediaObject\MediaObjectReferences;
use CultuurNet\UDB3\Model\ValueObject\MediaObject\MediaObjectType;
use CultuurNet\UDB3\Model\ValueObject\Moderation\WorkflowStatus;
use CultuurNet\UDB3\Model\ValueObject\Price\PriceInfo;
use CultuurNet\UDB3\Model\ValueObject\Price\Tariff;
use CultuurNet\UDB3\Model\ValueObject\Price\TariffName;
use CultuurNet\UDB3\Model\ValueObject\Price\Tariffs;
use CultuurNet\UDB3\Model\ValueObject\Price\TranslatedTariffName;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Category;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryDomain;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryLabel;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Label\Label;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Label\LabelName;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Label\Labels;
use CultuurNet\UDB3\Model\ValueObject\Text\Description;
use CultuurNet\UDB3\Model\ValueObject\Text\Title;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedDescription;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use CultuurNet\UDB3\Model\ValueObject\Web\EmailAddress;
use CultuurNet\UDB3\Model\ValueObject\Web\EmailAddresses;
use CultuurNet\UDB3\Model\ValueObject\Web\TranslatedWebsiteLabel;
use CultuurNet\UDB3\Model\ValueObject\Web\Url;
use CultuurNet\UDB3\Model\ValueObject\Web\Urls;
use CultuurNet\UDB3\Model\ValueObject\Web\WebsiteLabel;
use CultuurNet\UDB3\Model\ValueObject\Web\WebsiteLink;
use Money\Currency;
use Money\Money;
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
    public function it_should_denormalize_event_data_with_terms_with_labels_and_domains()
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
                    'label' => 'concert',
                    'domain' => 'eventtype',
                ],
                [
                    'id' => '0.50.2.0.1',
                    'label' => 'blues',
                    'domain' => 'theme',
                ],
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
                    new CategoryID('0.50.1.0.1'),
                    new CategoryLabel('concert'),
                    new CategoryDomain('eventtype')
                ),
                new Category(
                    new CategoryID('0.50.2.0.1'),
                    new CategoryLabel('blues'),
                    new CategoryDomain('theme')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($eventData, ImmutableEvent::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_event_data_with_optional_properties()
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
            'description' => [
                'nl' => 'Voorbeeld beschrijving',
                'en' => 'Example description',
            ],
            'labels' => [
                'foo',
                'bar',
            ],
            'hiddenLabels' => [
                'lorem',
                'ipsum',
            ],
            'organizer' => [
                '@id' => 'https://io.uitdatabank.be/organizers/236f736e-5308-4c3a-94f3-da0bd768da7d',
            ],
            'typicalAgeRange' => '8-12',
            'audience' => [
                'audienceType' => 'education',
            ],
            'priceInfo' => [
                [
                    'category' => 'tariff',
                    'name' => [
                        'nl' => 'Senioren',
                        'en' => 'Seniors',
                    ],
                    'price' => 10.5,
                    'priceCurrency' => 'EUR',
                ],
                [
                    'category' => 'base',
                    'name' => [
                        'en' => 'Base tariff',
                        'nl' => 'Basistarief',
                    ],
                    'price' => 15,
                    'priceCurrency' => 'EUR',
                ],
            ],
            'bookingInfo' => [
                'phone' => '02 551 18 70',
                'email' => 'info@publiq.be',
                'url' => 'https://www.publiq.be',
                'urlLabel' => [
                    'nl' => 'Publiq',
                    'fr' => 'Publiq FR',
                ],
                'availabilityStarts' => '2018-01-01T00:00:00+01:00',
                'availabilityEnds' => '2018-10-01T00:00:00+01:00',
            ],
            'contactPoint' => [
                'phone' => [
                    '044/556677',
                    '011/223344',
                ],
                'email' => [
                    'foo@publiq.be',
                    'bar@publiq.be',
                ],
                'url' => [
                    'https://www.uitdatabank.be',
                    'https://www.uitpas.be',
                ],
            ],
            'mediaObject' => [
                [
                    '@id' => 'https://io.uitdatabank.be/media/8b3c82d5-6cfe-442e-946c-1f4452636d61',
                    'description' => 'Example image 1',
                    'copyrightHolder' => 'Alice',
                    'inLanguage' => 'nl',
                ],
                [
                    '@id' => 'https://io.uitdatabank.be/media/fc712fef-e7c9-4df6-8655-da943852bd8d',
                    '@type' => 'schema:ImageObject',
                    'description' => 'Example image 2',
                    'copyrightHolder' => 'Bob',
                    'inLanguage' => 'fr',
                    'contentUrl' => 'https://io.uitdatabank.be/media/fc712fef-e7c9-4df6-8655-da943852bd8d.png',
                    'thumbnailUrl' => 'https://io.uitdatabank.be/media/fc712fef-e7c9-4df6-8655-da943852bd8d.png',
                ],
            ],
            'workflowStatus' => 'APPROVED',
            'availableFrom' => '2018-01-01T00:00:00+01:00',
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

        $expected = $expected
            ->withDescription(
                (new TranslatedDescription(new Language('nl'), new Description('Voorbeeld beschrijving')))
                    ->withTranslation(new Language('en'), new Description('Example description'))
            )
            ->withAvailableFrom(
                \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-01T00:00:00+01:00')
            )
            ->withLabels(
                new Labels(
                    new Label(new LabelName('foo'), true),
                    new Label(new LabelName('bar'), true),
                    new Label(new LabelName('lorem'), false),
                    new Label(new LabelName('ipsum'), false)
                )
            )
            ->withOrganizerReference(
                OrganizerReference::createWithOrganizerId(new UUID('236f736e-5308-4c3a-94f3-da0bd768da7d'))
            )
            ->withAgeRange(
                new AgeRange(new Age(8), new Age(12))
            )
            ->withAudienceType(
                AudienceType::education()
            )
            ->withPriceInfo(
                new PriceInfo(
                    new Tariff(
                        (new TranslatedTariffName(
                            new Language('nl'),
                            new TariffName('Basistarief')
                        ))->withTranslation(new Language('en'), new TariffName('Base tariff')),
                        new Money(
                            1500,
                            new Currency('EUR')
                        )
                    ),
                    new Tariffs(
                        new Tariff(
                            (new TranslatedTariffName(
                                new Language('nl'),
                                new TariffName('Senioren')
                            ))->withTranslation(new Language('en'), new TariffName('Seniors')),
                            new Money(
                                1050,
                                new Currency('EUR')
                            )
                        )
                    )
                )
            )
            ->withBookingInfo(
                new BookingInfo(
                    new WebsiteLink(
                        new Url('https://www.publiq.be'),
                        (new TranslatedWebsiteLabel(
                            new Language('nl'),
                            new WebsiteLabel('Publiq')
                        ))->withTranslation(new Language('fr'), new WebsiteLabel('Publiq FR'))
                    ),
                    new TelephoneNumber('02 551 18 70'),
                    new EmailAddress('info@publiq.be'),
                    new BookingAvailability(
                        \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-01-01T00:00:00+01:00'),
                        \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2018-10-01T00:00:00+01:00')
                    )
                )
            )
            ->withContactPoint(
                new ContactPoint(
                    new TelephoneNumbers(
                        new TelephoneNumber('044/556677'),
                        new TelephoneNumber('011/223344')
                    ),
                    new EmailAddresses(
                        new EmailAddress('foo@publiq.be'),
                        new EmailAddress('bar@publiq.be')
                    ),
                    new Urls(
                        new Url('https://www.uitdatabank.be'),
                        new Url('https://www.uitpas.be')
                    )
                )
            )
            ->withMediaObjectReferences(
                new MediaObjectReferences(
                    MediaObjectReference::createWithMediaObjectId(
                        new UUID('8b3c82d5-6cfe-442e-946c-1f4452636d61'),
                        new Description('Example image 1'),
                        new CopyrightHolder('Alice'),
                        new Language('nl')
                    ),
                    MediaObjectReference::createWithEmbeddedMediaObject(
                        new MediaObject(
                            new UUID('fc712fef-e7c9-4df6-8655-da943852bd8d'),
                            MediaObjectType::imageObject(),
                            new Url('https://io.uitdatabank.be/media/fc712fef-e7c9-4df6-8655-da943852bd8d.png'),
                            new Url('https://io.uitdatabank.be/media/fc712fef-e7c9-4df6-8655-da943852bd8d.png')
                        ),
                        new Description('Example image 2'),
                        new CopyrightHolder('Bob'),
                        new Language('fr')
                    )
                )
            )
            ->withWorkflowStatus(
                WorkflowStatus::APPROVED()
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
