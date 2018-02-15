<?php

namespace CultuurNet\UDB3\Model\ValueObject\Text;

use PHPUnit\Framework\TestCase;

class TranslatedTitleTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_only_accept_a_title_as_original_text_value()
    {
        $className = Title::class;
        $invalidClassName = Description::class;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("The given object is a {$invalidClassName}, expected {$className}.");

        new TranslatedTitle(new Language('nl'), new Description('foo'));
    }

    /**
     * @test
     */
    public function it_should_start_with_one_language_and_title_and_be_translatable()
    {
        $nl = new Language('nl');
        $fr = new Language('fr');
        $en = new Language('en');

        $nlValue = new Title('foo');
        $frValue = new Title('bar');
        $enValue = new Title('lorem');

        $translatedTitle = (new TranslatedTitle($nl, $nlValue))
            ->withTranslation($fr, $frValue)
            ->withTranslation($en, $enValue);

        $this->assertEquals($nlValue, $translatedTitle->getTranslation($nl));
        $this->assertEquals($frValue, $translatedTitle->getTranslation($fr));
        $this->assertEquals($enValue, $translatedTitle->getTranslation($en));
    }

    /**
     * @test
     */
    public function it_should_only_accept_a_title_as_translation()
    {
        $nl = new Language('nl');
        $nlValue = new Title('foo');
        $translatedTitle = (new TranslatedTitle($nl, $nlValue));

        $className = Title::class;
        $invalidClassName = Description::class;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("The given object is a {$invalidClassName}, expected {$className}.");

        $translatedTitle->withTranslation(new Language('fr'), new Description('foo'));
    }

    /**
     * @test
     */
    public function it_should_be_able_to_remove_a_translation()
    {
        $nl = new Language('nl');
        $fr = new Language('fr');
        $en = new Language('en');

        $nlValue = new Title('foo');
        $frValue = new Title('bar');
        $enValue = new Title('lorem');

        $translatedTitle = (new TranslatedTitle($nl, $nlValue))
            ->withTranslation($fr, $frValue)
            ->withTranslation($en, $enValue)
            ->withoutTranslation($fr);

        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage("No translation found for language fr");

        $translatedTitle->getTranslation($fr);
    }

    /**
     * @test
     */
    public function it_should_not_be_able_to_remove_the_original_language()
    {
        $nl = new Language('nl');
        $fr = new Language('fr');
        $en = new Language('en');

        $nlValue = new Title('foo');
        $frValue = new Title('bar');
        $enValue = new Title('lorem');

        $translatedTitle = (new TranslatedTitle($nl, $nlValue))
            ->withTranslation($fr, $frValue)
            ->withTranslation($en, $enValue);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Can not remove translation of the original language.');

        $translatedTitle->withoutTranslation($nl);
    }

    /**
     * @test
     */
    public function it_should_return_the_original_language()
    {
        $nl = new Language('nl');
        $nlValue = new Title('foo');

        $translatedTitle = (new TranslatedTitle($nl, $nlValue));

        $this->assertEquals($nl, $translatedTitle->getOriginalLanguage());
    }

    /**
     * @test
     */
    public function it_should_return_all_languages()
    {
        $nl = new Language('nl');
        $fr = new Language('fr');
        $en = new Language('en');

        $nlValue = new Title('foo');
        $frValue = new Title('bar');
        $enValue = new Title('lorem');

        $translatedTitle = (new TranslatedTitle($nl, $nlValue))
            ->withTranslation($fr, $frValue)
            ->withTranslation($en, $enValue);

        $expectedLanguages = new Languages($nl, $fr, $en);
        $this->assertEquals($expectedLanguages, $translatedTitle->getLanguages());
    }

    /**
     * @test
     */
    public function it_should_return_all_languages_without_the_original_language()
    {
        $nl = new Language('nl');
        $fr = new Language('fr');
        $en = new Language('en');

        $nlValue = new Title('foo');
        $frValue = new Title('bar');
        $enValue = new Title('lorem');

        $translatedTitle = (new TranslatedTitle($nl, $nlValue))
            ->withTranslation($fr, $frValue)
            ->withTranslation($en, $enValue);

        $expectedLanguages = new Languages($fr, $en);
        $this->assertEquals($expectedLanguages, $translatedTitle->getLanguagesWithoutOriginal());
    }
}
