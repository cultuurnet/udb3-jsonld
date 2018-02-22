<?php

namespace CultuurNet\UDB3\Model\Event;

use CultuurNet\Geocoding\Coordinate\Coordinates;
use CultuurNet\Geocoding\Coordinate\Latitude;
use CultuurNet\Geocoding\Coordinate\Longitude;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarWithOpeningHours;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Day;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Days;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Hour;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Minute;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHour;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Time;
use CultuurNet\UDB3\Model\ValueObject\Calendar\PermanentCalendar;
use CultuurNet\UDB3\Model\ValueObject\Geography\Address;
use CultuurNet\UDB3\Model\ValueObject\Geography\CountryCode;
use CultuurNet\UDB3\Model\ValueObject\Geography\Locality;
use CultuurNet\UDB3\Model\ValueObject\Geography\PostalCode;
use CultuurNet\UDB3\Model\ValueObject\Geography\Street;
use CultuurNet\UDB3\Model\ValueObject\Geography\TranslatedAddress;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Category;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryDomain;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryLabel;
use CultuurNet\UDB3\Model\ValueObject\Text\Title;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use PHPUnit\Framework\TestCase;

class ImmutablePlaceTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_return_the_injected_calendar()
    {
        $calendar = $this->getCalendar();
        $place = $this->getPlace();

        $this->assertEquals($calendar, $place->getCalendar());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_an_updated_calendar()
    {
        $calendar = $this->getCalendar();
        $place = $this->getPlace();

        $days = new Days(
            Day::monday(),
            Day::tuesday(),
            Day::wednesday()
        );

        $openingHours = new OpeningHours(
            new OpeningHour(
                $days,
                new Time(
                    new Hour(9),
                    new Minute(0)
                ),
                $closingTime = new Time(
                    new Hour(12),
                    new Minute(0)
                )
            ),
            new OpeningHour(
                $days,
                new Time(
                    new Hour(13),
                    new Minute(0)
                ),
                $closingTime = new Time(
                    new Hour(17),
                    new Minute(0)
                )
            )
        );

        $updatedCalendar = new PermanentCalendar($openingHours);
        $updatedEvent = $place->withCalendar($updatedCalendar);

        $this->assertNotEquals($calendar, $updatedCalendar);
        $this->assertEquals($calendar, $place->getCalendar());
        $this->assertEquals($updatedCalendar, $updatedEvent->getCalendar());
    }

    /**
     * @test
     */
    public function it_should_return_the_injected_address()
    {
        $address = $this->getAddress();
        $place = $this->getPlace();

        $this->assertEquals($address, $place->getAddress());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_an_updated_address()
    {
        $address = $this->getAddress();
        $updatedAddress = $address->withTranslation(
            new Language('fr'),
            new Address(
                new Street('Quai du Hainaut 41-43'),
                new PostalCode('1080'),
                new Locality('Bruxelles'),
                new CountryCode('BE')
            )
        );

        $place = $this->getPlace();
        $updatedPlace = $place->withAddress($updatedAddress);

        $this->assertNotEquals($place, $updatedPlace);
        $this->assertEquals($address, $place->getAddress());
        $this->assertEquals($updatedAddress, $updatedPlace->getAddress());
    }

    /**
     * @test
     */
    public function it_should_return_no_coordinates_by_default()
    {
        $this->assertNull($this->getPlace()->getGeoCoordinates());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_updated_coordinates()
    {
        $coordinates = new Coordinates(
            new Latitude(45.123),
            new Longitude(132.456)
        );

        $place = $this->getPlace();
        $updatedPlace = $place->withGeoCoordinates($coordinates);

        $this->assertNull($place->getGeoCoordinates());
        $this->assertEquals($coordinates, $updatedPlace->getGeoCoordinates());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_without_coordinates()
    {
        $coordinates = new Coordinates(
            new Latitude(45.123),
            new Longitude(132.456)
        );

        $place = $this->getPlace()->withGeoCoordinates($coordinates);
        $updatedPlace = $place->withoutGeoCoordinates();

        $this->assertEquals($coordinates, $place->getGeoCoordinates());
        $this->assertNull($updatedPlace->getGeoCoordinates());
    }

    /**
     * @return UUID
     */
    private function getId()
    {
        return new UUID('aadcee95-6180-4924-a8eb-ed829d4957a2');
    }

    /**
     * @return Language
     */
    private function getMainLanguage()
    {
        return new Language('nl');
    }

    /**
     * @return TranslatedTitle
     */
    private function getTitle()
    {
        return new TranslatedTitle(
            $this->getMainLanguage(),
            new Title('foo')
        );
    }

    /**
     * @return CalendarWithOpeningHours
     */
    private function getCalendar()
    {
        return new PermanentCalendar(new OpeningHours());
    }

    /**
     * @return TranslatedAddress
     */
    private function getAddress()
    {
        $address = new Address(
            new Street('Henegouwenkaai 41-43'),
            new PostalCode('1080'),
            new Locality('Brussel'),
            new CountryCode('BE')
        );

        return new TranslatedAddress(new Language('nl'), $address);
    }

    /**
     * @return Categories
     */
    private function getTerms()
    {
        return new Categories(
            new Category(
                new CategoryID('0.50.1.0.0'),
                new CategoryLabel('concert'),
                new CategoryDomain('eventtype')
            )
        );
    }

    /**
     * @return ImmutablePlace
     */
    private function getPlace()
    {
        return new ImmutablePlace(
            $this->getId(),
            $this->getMainLanguage(),
            $this->getTitle(),
            $this->getCalendar(),
            $this->getAddress(),
            $this->getTerms()
        );
    }
}
