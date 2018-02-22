<?php

namespace CultuurNet\UDB3\Model\Event;

use CultuurNet\UDB3\Model\ValueObject\Audience\AudienceType;
use CultuurNet\UDB3\Model\ValueObject\Calendar\Calendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\CalendarBuilder;
use CultuurNet\UDB3\Model\ValueObject\Calendar\DateRange;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;
use CultuurNet\UDB3\Model\ValueObject\Calendar\PermanentCalendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\SingleDateRangeCalendar;
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

class ImmutableEventTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_return_the_injected_calendar()
    {
        $calendar = $this->getCalendar();
        $event = $this->getEvent();

        $this->assertEquals($calendar, $event->getCalendar());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_an_updated_calendar()
    {
        $calendar = $this->getCalendar();
        $event = $this->getEvent();

        $updatedCalendar = new PermanentCalendar(new OpeningHours());
        $updatedEvent = $event->withCalendar($updatedCalendar);

        $this->assertNotEquals($calendar, $updatedCalendar);
        $this->assertEquals($calendar, $event->getCalendar());
        $this->assertEquals($updatedCalendar, $updatedEvent->getCalendar());
    }

    /**
     * @test
     */
    public function it_should_return_everyone_as_the_default_audience()
    {
        $event = $this->getEvent();
        $expected = AudienceType::everyone();
        $this->assertTrue($event->getAudienceType()->sameAs($expected));
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_an_updated_audience()
    {
        $audience = AudienceType::everyone();
        $updatedAudience = AudienceType::members();

        $event = $this->getEvent();
        $updatedEvent = $event->withAudienceType($updatedAudience);

        $this->assertNotEquals($event, $updatedEvent);
        $this->assertTrue($event->getAudienceType()->sameAs($audience));
        $this->assertTrue($updatedEvent->getAudienceType()->sameAs($updatedAudience));
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
     * @return Calendar
     */
    private function getCalendar()
    {
        return new SingleDateRangeCalendar(
            \DateTimeImmutable::createFromFormat('d/m/Y', '10/01/2018'),
            \DateTimeImmutable::createFromFormat('d/m/Y', '11/01/2018')
        );
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
     * @return ImmutableEvent
     */
    private function getEvent()
    {
        return new ImmutableEvent(
            $this->getId(),
            $this->getTitle(),
            $this->getCalendar(),
            $this->getTerms()
        );
    }
}
