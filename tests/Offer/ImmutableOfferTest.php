<?php

namespace CultuurNet\UDB3\Model\Offer;

use CultuurNet\UDB3\Model\ValueObject\Audience\Age;
use CultuurNet\UDB3\Model\ValueObject\Audience\AgeRange;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Category;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryDomain;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryLabel;
use CultuurNet\UDB3\Model\ValueObject\Text\Description;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use CultuurNet\UDB3\Model\ValueObject\Text\Title;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedDescription;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use PHPUnit\Framework\TestCase;

class ImmutableOfferTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_the_initial_properties_and_some_sensible_defaults()
    {
        $offer = $this->getOffer();

        $this->assertEquals($this->getId(), $offer->getId());
        $this->assertEquals($this->getMainLanguage(), $offer->getMainLanguage());
        $this->assertEquals($this->getTitle(), $offer->getTitle());
        $this->assertEquals($this->getTerms(), $offer->getTerms());

        $this->assertNull($offer->getDescription());
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

        $offer = $this->getOffer();
        $updatedOffer = $offer->withTitle($updatedTitle);

        $this->assertNotEquals($updatedOffer, $offer);
        $this->assertEquals($originalTitle, $offer->getTitle());
        $this->assertEquals($updatedTitle, $updatedOffer->getTitle());
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

        $offer = $this->getOffer();
        $updatedOffer = $offer->withDescription($description);

        $this->assertNotEquals($updatedOffer, $offer);
        $this->assertNull($offer->getDescription());
        $this->assertEquals($description, $updatedOffer->getDescription());
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

        $offer = $this->getOffer()->withDescription($initialDescription);
        $updatedOffer = $offer->withDescription($updatedDescription);

        $this->assertNotEquals($updatedOffer, $offer);
        $this->assertEquals($initialDescription, $offer->getDescription());
        $this->assertEquals($updatedDescription, $updatedOffer->getDescription());
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

        $offer = $this->getOffer()->withDescription($description);
        $updatedOffer = $offer->withoutDescription();

        $this->assertNotEquals($offer, $updatedOffer);
        $this->assertEquals($description, $offer->getDescription());
        $this->assertNull($updatedOffer->getDescription());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_updated_terms()
    {
        $updatedTerms = new Categories(
            new Category(
                new CategoryID('0.50.1.0.0'),
                new CategoryLabel('concert'),
                new CategoryDomain('eventtype')
            ),
            new Category(
                new CategoryID('0.50.2.0.0'),
                new CategoryLabel('blues'),
                new CategoryDomain('theme')
            )
        );

        $offer = $this->getOffer();
        $updatedOffer = $offer->withTerms($updatedTerms);

        $this->assertNotEquals($offer, $updatedOffer);
        $this->assertEquals($this->getTerms(), $offer->getTerms());
        $this->assertEquals($updatedTerms, $updatedOffer->getTerms());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_an_age_range()
    {
        $ageRange = new AgeRange(new Age(8), new Age(12));

        $offer = $this->getOffer();
        $updatedOffer = $offer->withAgeRange($ageRange);

        $this->assertNotEquals($updatedOffer, $offer);
        $this->assertNull($offer->getAgeRange());
        $this->assertEquals($ageRange, $updatedOffer->getAgeRange());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_an_updated_age_range()
    {
        $initialAgeRange = new AgeRange(new Age(8), new Age(14));
        $updatedAgeRange = new AgeRange(new Age(8), new Age(12));

        $initialOffer = $this->getOffer()->withAgeRange($initialAgeRange);
        $updatedOffer = $initialOffer->withAgeRange($updatedAgeRange);

        $this->assertNotEquals($updatedOffer, $initialOffer);
        $this->assertEquals($initialAgeRange, $initialOffer->getAgeRange());
        $this->assertEquals($updatedAgeRange, $updatedOffer->getAgeRange());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_without_age_range()
    {
        $ageRange = new AgeRange(new Age(8), new Age(12));

        $initialOffer = $this->getOffer()->withAgeRange($ageRange);
        $updatedOffer = $initialOffer->withoutAgeRange();

        $this->assertNotEquals($updatedOffer, $initialOffer);
        $this->assertEquals($ageRange, $initialOffer->getAgeRange());
        $this->assertNull($updatedOffer->getAgeRange());
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
     * @return ImmutableOffer
     */
    private function getOffer()
    {
        return new MockImmutableOffer(
            $this->getId(),
            $this->getMainLanguage(),
            $this->getTitle(),
            $this->getTerms()
        );
    }
}
