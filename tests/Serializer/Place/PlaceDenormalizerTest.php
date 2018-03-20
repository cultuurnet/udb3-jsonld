<?php

namespace CultuurNet\UDB3\Model\Serializer\Place;

use CultuurNet\Geocoding\Coordinate\Coordinates;
use CultuurNet\Geocoding\Coordinate\Latitude;
use CultuurNet\Geocoding\Coordinate\Longitude;
use CultuurNet\UDB3\Model\Event\ImmutableEvent;
use CultuurNet\UDB3\Model\Organizer\OrganizerReference;
use CultuurNet\UDB3\Model\Place\ImmutablePlace;
use CultuurNet\UDB3\Model\ValueObject\Audience\Age;
use CultuurNet\UDB3\Model\ValueObject\Audience\AgeRange;
use CultuurNet\UDB3\Model\ValueObject\Calendar\DateRange;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Day;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Days;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Hour;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Minute;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHour;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Time;
use CultuurNet\UDB3\Model\ValueObject\Calendar\PeriodicCalendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\PermanentCalendar;
use CultuurNet\UDB3\Model\ValueObject\Geography\Address;
use CultuurNet\UDB3\Model\ValueObject\Geography\CountryCode;
use CultuurNet\UDB3\Model\ValueObject\Geography\Locality;
use CultuurNet\UDB3\Model\ValueObject\Geography\PostalCode;
use CultuurNet\UDB3\Model\ValueObject\Geography\Street;
use CultuurNet\UDB3\Model\ValueObject\Geography\TranslatedAddress;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Moderation\WorkflowStatus;
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
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\UnsupportedException;

class PlaceDenormalizerTest extends TestCase
{
    /**
     * @var PlaceDenormalizer
     */
    private $denormalizer;

    public function setUp()
    {
        $this->denormalizer = new PlaceDenormalizer();
    }

    /**
     * @test
     */
    public function it_should_denormalize_place_data_with_only_the_required_properties()
    {
        $placeData = [
            '@id' => 'https://io.uitdatabank.be/place/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Place',
            '@context' => '/contexts/place',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'address' => [
                'nl' => [
                    'streetAddress' => 'Henegouwenkaai 41-43',
                    'postalCode' => '1080',
                    'addressLocality' => 'Brussel',
                    'addressCountry' => 'BE',
                ],
            ],
            'calendarType' => 'permanent',
            'terms' => [
                [
                    'id' => '0.50.1.0.1',
                ]
            ],
        ];

        $expected = new ImmutablePlace(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ),
            new PermanentCalendar(new OpeningHours()),
            new TranslatedAddress(
                new Language('nl'),
                new Address(
                    new Street('Henegouwenkaai 41-43'),
                    new PostalCode('1080'),
                    new Locality('Brussel'),
                    new CountryCode('BE')
                )
            ),
            new Categories(
                new Category(
                    new CategoryID('0.50.1.0.1')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($placeData, ImmutablePlace::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_place_data_with_title_translations()
    {
        $placeData = [
            '@id' => 'https://io.uitdatabank.be/place/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Place',
            '@context' => '/contexts/place',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
                'en' => 'Example title',
                'fr' => 'Titre de l\'exemple',
            ],
            'address' => [
                'nl' => [
                    'streetAddress' => 'Henegouwenkaai 41-43',
                    'postalCode' => '1080',
                    'addressLocality' => 'Brussel',
                    'addressCountry' => 'BE',
                ],
            ],
            'calendarType' => 'permanent',
            'terms' => [
                [
                    'id' => '0.50.1.0.1',
                ]
            ],
        ];

        $expected = new ImmutablePlace(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            (new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ))
                ->withTranslation(new Language('en'), new Title('Example title'))
                ->withTranslation(new Language('fr'), new Title('Titre de l\'exemple')),
            new PermanentCalendar(new OpeningHours()),
            new TranslatedAddress(
                new Language('nl'),
                new Address(
                    new Street('Henegouwenkaai 41-43'),
                    new PostalCode('1080'),
                    new Locality('Brussel'),
                    new CountryCode('BE')
                )
            ),
            new Categories(
                new Category(
                    new CategoryID('0.50.1.0.1')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($placeData, ImmutablePlace::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_place_data_with_address_translations()
    {
        $placeData = [
            '@id' => 'https://io.uitdatabank.be/place/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Place',
            '@context' => '/contexts/place',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'address' => [
                'nl' => [
                    'streetAddress' => 'Henegouwenkaai 41-43',
                    'postalCode' => '1080',
                    'addressLocality' => 'Brussel',
                    'addressCountry' => 'BE',
                ],
                'fr' => [
                    'streetAddress' => 'Quai du Hainaut 41-43',
                    'postalCode' => '1080',
                    'addressLocality' => 'Bruxelles',
                    'addressCountry' => 'BE',
                ],
            ],
            'calendarType' => 'permanent',
            'terms' => [
                [
                    'id' => '0.50.1.0.1',
                ]
            ],
        ];

        $expected = new ImmutablePlace(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ),
            new PermanentCalendar(new OpeningHours()),
            (new TranslatedAddress(
                new Language('nl'),
                new Address(
                    new Street('Henegouwenkaai 41-43'),
                    new PostalCode('1080'),
                    new Locality('Brussel'),
                    new CountryCode('BE')
                )
            ))
                ->withTranslation(
                    new Language('fr'),
                    new Address(
                        new Street('Quai du Hainaut 41-43'),
                        new PostalCode('1080'),
                        new Locality('Bruxelles'),
                        new CountryCode('BE')
                    )
                ),
            new Categories(
                new Category(
                    new CategoryID('0.50.1.0.1')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($placeData, ImmutablePlace::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_place_data_with_a_periodic_calendar_and_no_opening_hours()
    {
        $placeData = [
            '@id' => 'https://io.uitdatabank.be/place/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Place',
            '@context' => '/contexts/place',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'address' => [
                'nl' => [
                    'streetAddress' => 'Henegouwenkaai 41-43',
                    'postalCode' => '1080',
                    'addressLocality' => 'Brussel',
                    'addressCountry' => 'BE',
                ],
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

        $expected = new ImmutablePlace(
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
            new TranslatedAddress(
                new Language('nl'),
                new Address(
                    new Street('Henegouwenkaai 41-43'),
                    new PostalCode('1080'),
                    new Locality('Brussel'),
                    new CountryCode('BE')
                )
            ),
            new Categories(
                new Category(
                    new CategoryID('0.50.1.0.1')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($placeData, ImmutablePlace::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_place_data_with_a_periodic_calendar_and_opening_hours()
    {
        $placeData = [
            '@id' => 'https://io.uitdatabank.be/place/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Place',
            '@context' => '/contexts/place',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'address' => [
                'nl' => [
                    'streetAddress' => 'Henegouwenkaai 41-43',
                    'postalCode' => '1080',
                    'addressLocality' => 'Brussel',
                    'addressCountry' => 'BE',
                ],
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

        $expected = new ImmutablePlace(
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
            new TranslatedAddress(
                new Language('nl'),
                new Address(
                    new Street('Henegouwenkaai 41-43'),
                    new PostalCode('1080'),
                    new Locality('Brussel'),
                    new CountryCode('BE')
                )
            ),
            new Categories(
                new Category(
                    new CategoryID('0.50.1.0.1')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($placeData, ImmutablePlace::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_place_data_with_a_permanent_calendar_and_opening_hours()
    {
        $placeData = [
            '@id' => 'https://io.uitdatabank.be/place/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Place',
            '@context' => '/contexts/place',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'address' => [
                'nl' => [
                    'streetAddress' => 'Henegouwenkaai 41-43',
                    'postalCode' => '1080',
                    'addressLocality' => 'Brussel',
                    'addressCountry' => 'BE',
                ],
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

        $expected = new ImmutablePlace(
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
            new TranslatedAddress(
                new Language('nl'),
                new Address(
                    new Street('Henegouwenkaai 41-43'),
                    new PostalCode('1080'),
                    new Locality('Brussel'),
                    new CountryCode('BE')
                )
            ),
            new Categories(
                new Category(
                    new CategoryID('0.50.1.0.1')
                )
            )
        );

        $actual = $this->denormalizer->denormalize($placeData, ImmutablePlace::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_place_data_with_terms_with_labels_and_domains()
    {
        $placeData = [
            '@id' => 'https://io.uitdatabank.be/place/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Place',
            '@context' => '/contexts/place',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'address' => [
                'nl' => [
                    'streetAddress' => 'Henegouwenkaai 41-43',
                    'postalCode' => '1080',
                    'addressLocality' => 'Brussel',
                    'addressCountry' => 'BE',
                ],
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

        $expected = new ImmutablePlace(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ),
            new PermanentCalendar(new OpeningHours()),
            new TranslatedAddress(
                new Language('nl'),
                new Address(
                    new Street('Henegouwenkaai 41-43'),
                    new PostalCode('1080'),
                    new Locality('Brussel'),
                    new CountryCode('BE')
                )
            ),
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

        $actual = $this->denormalizer->denormalize($placeData, ImmutablePlace::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_place_data_with_optional_properties()
    {
        $placeData = [
            '@id' => 'https://io.uitdatabank.be/place/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Place',
            '@context' => '/contexts/place',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'address' => [
                'nl' => [
                    'streetAddress' => 'Henegouwenkaai 41-43',
                    'postalCode' => '1080',
                    'addressLocality' => 'Brussel',
                    'addressCountry' => 'BE',
                ],
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
            'geo' => [
                "latitude" => 50.8793916,
                "longitude" => 4.7019674,
            ],
            'typicalAgeRange' => '8-12',
            'workflowStatus' => 'APPROVED',
            'availableFrom' => '2018-01-01T00:00:00+01:00',
        ];

        $expected = new ImmutablePlace(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ),
            new PermanentCalendar(new OpeningHours()),
            new TranslatedAddress(
                new Language('nl'),
                new Address(
                    new Street('Henegouwenkaai 41-43'),
                    new PostalCode('1080'),
                    new Locality('Brussel'),
                    new CountryCode('BE')
                )
            ),
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
            ->withGeoCoordinates(
                new Coordinates(
                    new Latitude(50.8793916),
                    new Longitude(4.7019674)
                )
            )
            ->withAgeRange(
                new AgeRange(new Age(8), new Age(12))
            )
            ->withWorkflowStatus(
                WorkflowStatus::APPROVED()
            );

        $actual = $this->denormalizer->denormalize($placeData, ImmutablePlace::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_when_trying_to_denormalize_to_an_unsupported_class()
    {
        $this->expectException(UnsupportedException::class);
        $this->denormalizer->denormalize([], ImmutableEvent::class);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_when_trying_to_denormalize_data_that_is_not_an_array()
    {
        $this->expectException(UnsupportedException::class);
        $this->denormalizer->denormalize(new \stdClass(), ImmutablePlace::class);
    }

    /**
     * @test
     */
    public function it_should_support_denormalization_to_immutable_place()
    {
        $this->assertTrue(
            $this->denormalizer->supportsDenormalization([], ImmutablePlace::class)
        );
    }
}
