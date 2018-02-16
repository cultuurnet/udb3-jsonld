<?php

namespace CultuurNet\UDB3\Model\Offer;

use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\CategoryID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\CategoryLabel;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Facilities;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Facility;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Terms;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Theme;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term\Type;
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
        $this->assertEquals($this->getType(), $offer->getType());

        $this->assertNull($offer->getTheme());
        $this->assertNull($offer->getDescription());

        $this->assertEquals(new Facilities(), $offer->getFacilities());
        $this->assertEquals(new Terms($this->getType()), $offer->getTerms());
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
    public function it_should_return_a_copy_with_an_updated_type()
    {
        $originalType = $this->getType();
        $updatedType = new Type(new CategoryID('0.26.1.0.0'), new CategoryLabel('exhibition'));

        $offer = $this->getOffer();
        $updatedOffer = $offer->withType($updatedType);

        $this->assertNotEquals($updatedOffer, $offer);
        $this->assertEquals($originalType, $offer->getType());
        $this->assertEquals($updatedType, $updatedOffer->getType());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_a_theme()
    {
        $theme = new Theme(new CategoryID('0.50.4.1.0'), new CategoryLabel('blues'));

        $offer = $this->getOffer();
        $updatedOffer = $offer->withTheme($theme);

        $this->assertNotEquals($updatedOffer, $offer);
        $this->assertNull($offer->getTheme());
        $this->assertEquals($theme, $updatedOffer->getTheme());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_with_an_updated_theme()
    {
        $theme = new Theme(new CategoryID('0.50.4.1.0'), new CategoryLabel('blues'));
        $updatedTheme = new Theme(new CategoryID('0.50.4.2.0'), new CategoryLabel('jazz'));

        $offer = $this->getOffer()->withTheme($theme);
        $updatedOffer = $offer->withTheme($updatedTheme);

        $this->assertNotEquals($updatedOffer, $offer);
        $this->assertEquals($theme, $offer->getTheme());
        $this->assertEquals($updatedTheme, $updatedOffer->getTheme());
    }

    /**
     * @test
     */
    public function it_should_return_a_copy_without_a_theme()
    {
        $theme = new Theme(new CategoryID('0.50.4.1.0'), new CategoryLabel('blues'));

        $offer = $this->getOffer()->withTheme($theme);
        $updatedOffer = $offer->withoutTheme();

        $this->assertNotEquals($updatedOffer, $offer);
        $this->assertEquals($theme, $offer->getTheme());
        $this->assertNull($updatedOffer->getTheme());
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

        $offer = $this->getOffer();
        $updatedOffer = $offer->withFacilities($facilities);

        $this->assertNotEquals($offer, $updatedOffer);
        $this->assertEquals(new Facilities(), $offer->getFacilities());
        $this->assertEquals($facilities, $updatedOffer->getFacilities());
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

        $offer = $this->getOffer()->withFacilities($facilities);
        $updatedOffer = $offer->withFacilities($updatedFacilities);

        $this->assertNotEquals($offer, $updatedOffer);
        $this->assertEquals($facilities, $offer->getFacilities());
        $this->assertEquals($updatedFacilities, $updatedOffer->getFacilities());
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

        $offer = $this->getOffer()->withFacilities($facilities);
        $updatedOffer = $offer->withoutFacilities();

        $this->assertNotEquals($offer, $updatedOffer);
        $this->assertEquals($facilities, $offer->getFacilities());
        $this->assertEquals(new Facilities(), $updatedOffer->getFacilities());
    }

    /**
     * @test
     * @dataProvider termsDataProvider
     *
     * @param Offer $offer
     * @param Terms $expectedTerms
     */
    public function it_should_return_all_terms(Offer $offer, Terms $expectedTerms)
    {
        $this->assertEquals($expectedTerms, $offer->getTerms());
    }

    /**
     * @return array
     */
    public function termsDataProvider()
    {
        $offer = $this->getOffer();
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
            'offer_with_type' => [
                'offer' => $offer,
                'expectedTerms' => new Terms($type),
            ],
            'offer_with_type_and_theme' => [
                'offer' => $offer->withTheme($theme),
                'expectedTerms' => new Terms($type, $theme),
            ],
            'offer_with_type_and_facilities' => [
                'offer' => $offer->withFacilities($facilities),
                'expectedTerms' => new Terms($type, ...$facilities->toArray()),
            ],
            'offer_with_type_and_theme_and_facilities' => [
                'offer' => $offer->withTheme($theme)->withFacilities($facilities),
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
     * @return ImmutableOffer
     */
    private function getOffer()
    {
        return new MockImmutableOffer(
            $this->getId(),
            $this->getMainLanguage(),
            $this->getTitle(),
            $this->getType()
        );
    }
}
