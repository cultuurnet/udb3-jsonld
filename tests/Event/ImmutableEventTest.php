<?php

namespace CultuurNet\UDB3\Model\Event;

use CultuurNet\UDB3\Model\ValueObject\Audience\Audience;
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
    public function it_should_return_everyone_as_the_default_audience()
    {
        $event = $this->getEvent();
        $expected = Audience::everyone();
        $this->assertTrue($event->getAudience()->sameAs($expected));
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_an_updated_audience()
    {
        $audience = Audience::everyone();
        $updatedAudience = Audience::members();

        $event = $this->getEvent();
        $updatedEvent = $event->withAudience($updatedAudience);

        $this->assertNotEquals($event, $updatedEvent);
        $this->assertTrue($event->getAudience()->sameAs($audience));
        $this->assertTrue($updatedEvent->getAudience()->sameAs($updatedAudience));
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
            $this->getMainLanguage(),
            $this->getTitle(),
            $this->getTerms()
        );
    }
}
