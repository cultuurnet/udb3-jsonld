<?php

namespace CultuurNet\UDB3\Model\ValueObject\Text;

use PHPUnit\Framework\TestCase;

class TranslatedDescriptionTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_only_accept_a_description_as_original_text_value()
    {
        $className = Description::class;
        $invalidClassName = Title::class;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("The given object is a {$invalidClassName}, expected {$className}.");

        new TranslatedDescription(new Language('nl'), new Title('foo'));
    }

    /**
     * @test
     */
    public function it_should_start_with_one_language_and_title_and_be_translatable()
    {
        $nl = new Language('nl');
        $fr = new Language('fr');
        $en = new Language('en');

        $nlValue = new Description('foo');
        $frValue = new Description('bar');
        $enValue = new Description('lorem');

        $translatedDescription = (new TranslatedDescription($nl, $nlValue))
            ->withTranslation($fr, $frValue)
            ->withTranslation($en, $enValue);

        $this->assertEquals($nlValue, $translatedDescription->getTranslation($nl));
        $this->assertEquals($frValue, $translatedDescription->getTranslation($fr));
        $this->assertEquals($enValue, $translatedDescription->getTranslation($en));
    }

    /**
     * @test
     */
    public function it_should_only_accept_a_title_as_translation()
    {
        $nl = new Language('nl');
        $nlValue = new Description('foo');
        $translatedDescription = (new TranslatedDescription($nl, $nlValue));

        $className = Description::class;
        $invalidClassName = Title::class;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("The given object is a {$invalidClassName}, expected {$className}.");

        $translatedDescription->withTranslation(new Language('fr'), new Title('foo'));
    }

    /**
     * @test
     */
    public function it_should_be_able_to_remove_a_translation()
    {
        $nl = new Language('nl');
        $fr = new Language('fr');
        $en = new Language('en');

        $nlValue = new Description('foo');
        $frValue = new Description('bar');
        $enValue = new Description('lorem');

        $translatedDescription = (new TranslatedDescription($nl, $nlValue))
            ->withTranslation($fr, $frValue)
            ->withTranslation($en, $enValue)
            ->withoutTranslation($fr);

        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage("No translation found for language fr");

        $translatedDescription->getTranslation($fr);
    }

    /**
     * @test
     */
    public function it_should_not_be_able_to_remove_the_original_language()
    {
        $nl = new Language('nl');
        $fr = new Language('fr');
        $en = new Language('en');

        $nlValue = new Description('foo');
        $frValue = new Description('bar');
        $enValue = new Description('lorem');

        $translatedDescription = (new TranslatedDescription($nl, $nlValue))
            ->withTranslation($fr, $frValue)
            ->withTranslation($en, $enValue);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Can not remove translation of the original language.');

        $translatedDescription->withoutTranslation($nl);
    }

    /**
     * @test
     */
    public function it_should_return_the_original_language()
    {
        $nl = new Language('nl');
        $nlValue = new Description('foo');

        $translatedDescription = (new TranslatedDescription($nl, $nlValue));

        $this->assertEquals($nl, $translatedDescription->getOriginalLanguage());
    }

    /**
     * @test
     */
    public function it_should_return_all_languages()
    {
        $nl = new Language('nl');
        $fr = new Language('fr');
        $en = new Language('en');

        $nlValue = new Description('foo');
        $frValue = new Description('bar');
        $enValue = new Description('lorem');

        $translatedDescription = (new TranslatedDescription($nl, $nlValue))
            ->withTranslation($fr, $frValue)
            ->withTranslation($en, $enValue);

        $expectedLanguages = new Languages($nl, $fr, $en);
        $this->assertEquals($expectedLanguages, $translatedDescription->getLanguages());
    }

    /**
     * @test
     */
    public function it_should_return_all_languages_without_the_original_language()
    {
        $nl = new Language('nl');
        $fr = new Language('fr');
        $en = new Language('en');

        $nlValue = new Description('foo');
        $frValue = new Description('bar');
        $enValue = new Description('lorem');

        $translatedDescription = (new TranslatedDescription($nl, $nlValue))
            ->withTranslation($fr, $frValue)
            ->withTranslation($en, $enValue);

        $expectedLanguages = new Languages($fr, $en);
        $this->assertEquals($expectedLanguages, $translatedDescription->getLanguagesWithoutOriginal());
    }
}
