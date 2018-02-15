<?php

namespace CultuurNet\UDB3\Model\Event;

use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\CategoryID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\CategoryLabel;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Facilities;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Facility;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Terms;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Theme;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Type;
use CultuurNet\UDB3\Model\ValueObject\Text\Description;
use CultuurNet\UDB3\Model\ValueObject\Text\Language;
use CultuurNet\UDB3\Model\ValueObject\Text\Title;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedDescription;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use PHPUnit\Framework\TestCase;

class ImmutableEventTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_the_initial_properties_and_some_sensible_defaults()
    {
        $event = $this->getEvent();

        $this->assertEquals($this->getId(), $event->getId());
        $this->assertEquals($this->getMainLanguage(), $event->getMainLanguage());
        $this->assertEquals($this->getTitle(), $event->getTitle());
        $this->assertEquals($this->getType(), $event->getType());

        $this->assertNull($event->getTheme());
        $this->assertNull($event->getDescription());

        $this->assertEquals(new Facilities(), $event->getFacilities());
        $this->assertEquals(new Terms($this->getType()), $event->getTerms());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_an_updated_title()
    {
        $originalTitle = $this->getTitle();
        $updatedTitle = $this->getTitle()
            ->withTranslation(new Language('nl'), new Title('foo UPDATED'))
            ->withTranslation(new Language('en'), new Title('bar'));

        $event = $this->getEvent();
        $updatedEvent = $event->withTitle($updatedTitle);

        $this->assertNotEquals($updatedEvent, $event);
        $this->assertEquals($originalTitle, $event->getTitle());
        $this->assertEquals($updatedTitle, $updatedEvent->getTitle());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_a_description()
    {
        $description = new TranslatedDescription(
            new Language('nl'),
            new Description('lorem')
        );

        $event = $this->getEvent();
        $updatedEvent = $event->withDescription($description);

        $this->assertNotEquals($updatedEvent, $event);
        $this->assertNull($event->getDescription());
        $this->assertEquals($description, $updatedEvent->getDescription());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_an_updated_description()
    {
        $initialDescription = new TranslatedDescription(
            new Language('nl'),
            new Description('lorem')
        );

        $updatedDescription = $initialDescription
            ->withTranslation(new Language('fr'), new Description('ipsum'));

        $event = $this->getEvent()->withDescription($initialDescription);
        $updatedEvent = $event->withDescription($updatedDescription);

        $this->assertNotEquals($updatedEvent, $event);
        $this->assertEquals($initialDescription, $event->getDescription());
        $this->assertEquals($updatedDescription, $updatedEvent->getDescription());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_without_description()
    {
        $description = new TranslatedDescription(
            new Language('nl'),
            new Description('lorem')
        );

        $event = $this->getEvent()->withDescription($description);
        $updatedEvent = $event->withoutDescription();

        $this->assertNotEquals($event, $updatedEvent);
        $this->assertEquals($description, $event->getDescription());
        $this->assertNull($updatedEvent->getDescription());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_an_updated_type()
    {
        $originalType = $this->getType();
        $updatedType = new Type(new CategoryID('0.26.1.0.0'), new CategoryLabel('exhibition'));

        $event = $this->getEvent();
        $updatedEvent = $event->withType($updatedType);

        $this->assertNotEquals($updatedEvent, $event);
        $this->assertEquals($originalType, $event->getType());
        $this->assertEquals($updatedType, $updatedEvent->getType());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_a_theme()
    {
        $theme = new Theme(new CategoryID('0.50.4.1.0'), new CategoryLabel('blues'));

        $event = $this->getEvent();
        $updatedEvent = $event->withTheme($theme);

        $this->assertNotEquals($updatedEvent, $event);
        $this->assertNull($event->getTheme());
        $this->assertEquals($theme, $updatedEvent->getTheme());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_an_updated_theme()
    {
        $theme = new Theme(new CategoryID('0.50.4.1.0'), new CategoryLabel('blues'));
        $updatedTheme = new Theme(new CategoryID('0.50.4.2.0'), new CategoryLabel('jazz'));

        $event = $this->getEvent()->withTheme($theme);
        $updatedEvent = $event->withTheme($updatedTheme);

        $this->assertNotEquals($updatedEvent, $event);
        $this->assertEquals($theme, $event->getTheme());
        $this->assertEquals($updatedTheme, $updatedEvent->getTheme());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_without_a_theme()
    {
        $theme = new Theme(new CategoryID('0.50.4.1.0'), new CategoryLabel('blues'));

        $event = $this->getEvent()->withTheme($theme);
        $updatedEvent = $event->withoutTheme();

        $this->assertNotEquals($updatedEvent, $event);
        $this->assertEquals($theme, $event->getTheme());
        $this->assertNull($updatedEvent->getTheme());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_facilities()
    {
        $facilities = new Facilities(
            new Facility(
                new CategoryID('0.100.1.0.0'),
                new CategoryLabel('wheelchair accessibility')
            )
        );

        $event = $this->getEvent();
        $updatedEvent = $event->withFacilities($facilities);

        $this->assertNotEquals($event, $updatedEvent);
        $this->assertEquals(new Facilities(), $event->getFacilities());
        $this->assertEquals($facilities, $updatedEvent->getFacilities());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_updated_facilities()
    {
        $facilities = new Facilities(
            new Facility(
                new CategoryID('0.100.1.0.0'),
                new CategoryLabel('wheelchair accessibility')
            )
        );

        $updatedFacilities = new Facilities(
            new Facility(
                new CategoryID('0.100.1.0.0'),
                new CategoryLabel('wheelchair accessibility')
            ),
            new Facility(
                new CategoryID('0.100.2.0.0'),
                new CategoryLabel('audio guide')
            )
        );

        $event = $this->getEvent()->withFacilities($facilities);
        $updatedEvent = $event->withFacilities($updatedFacilities);

        $this->assertNotEquals($event, $updatedEvent);
        $this->assertEquals($facilities, $event->getFacilities());
        $this->assertEquals($updatedFacilities, $updatedEvent->getFacilities());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_without_facilities()
    {
        $facilities = new Facilities(
            new Facility(
                new CategoryID('0.100.1.0.0'),
                new CategoryLabel('wheelchair accessibility')
            )
        );

        $event = $this->getEvent()->withFacilities($facilities);
        $updatedEvent = $event->withoutFacilities();

        $this->assertNotEquals($event, $updatedEvent);
        $this->assertEquals($facilities, $event->getFacilities());
        $this->assertEquals(new Facilities(), $updatedEvent->getFacilities());
    }

    /**
     * @test
     * @dataProvider termsDataProvider
     *
     * @param Event $event
     * @param Terms $expectedTerms
     */
    public function it_should_return_all_terms(Event $event, Terms $expectedTerms)
    {
        $this->assertEquals($expectedTerms, $event->getTerms());
    }

    /**
     * @return array
     */
    public function termsDataProvider()
    {
        $event = $this->getEvent();
        $type = $this->getType();
        $theme = new Theme(new CategoryID('0.50.4.1.0'), new CategoryLabel('blues'));

        $facilities = new Facilities(
            new Facility(
                new CategoryID('0.100.1.0.0'),
                new CategoryLabel('wheelchair accessibility')
            ),
            new Facility(
                new CategoryID('0.100.2.0.0'),
                new CategoryLabel('audio guide')
            )
        );

        return [
            'event_with_type' => [
                'event' => $event,
                'expectedTerms' => new Terms($type),
            ],
            'event_with_type_and_theme' => [
                'event' => $event->withTheme($theme),
                'expectedTerms' => new Terms($type, $theme),
            ],
            'event_with_type_and_facilities' => [
                'event' => $event->withFacilities($facilities),
                'expectedTerms' => new Terms($type, ...$facilities->toArray()),
            ],
            'event_with_type_and_theme_and_facilities' => [
                'event' => $event->withTheme($theme)->withFacilities($facilities),
                'expectedTerms' => new Terms($type, $theme, ...$facilities->toArray()),
            ],
        ];
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
     * @return Type
     */
    private function getType()
    {
        return new Type(
            new CategoryID('0.50.1.0.0'),
            new CategoryLabel('concert')
        );
    }

    /**
     * @return ImmutableEvent
     */
    private function getEvent()
    {
        return new ImmutableEvent(
            $this->getId(),
            $this->getMainLanguage(),
            $this->getTitle(),
            $this->getType()
        );
    }
}
