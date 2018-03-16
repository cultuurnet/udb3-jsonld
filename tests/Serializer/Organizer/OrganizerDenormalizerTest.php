<?php

namespace CultuurNet\UDB3\Model\Serializer\Organizer;

use CultuurNet\UDB3\Model\Event\ImmutableEvent;
use CultuurNet\UDB3\Model\Organizer\ImmutableOrganizer;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Text\Title;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use CultuurNet\UDB3\Model\ValueObject\Web\Url;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\UnsupportedException;

class OrganizerDenormalizerTest extends TestCase
{
    /**
     * @var OrganizerDenormalizer
     */
    private $denormalizer;

    public function setUp()
    {
        $this->denormalizer = new OrganizerDenormalizer();
    }

    /**
     * @test
     */
    public function it_should_denormalize_organizer_data_with_only_the_required_properties()
    {
        $organizerData = [
            '@id' => 'https://io.uitdatabank.be/organizer/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Organizer',
            '@context' => '/contexts/organizer',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
        ];

        $expected = new ImmutableOrganizer(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            )
        );

        $actual = $this->denormalizer->denormalize($organizerData, ImmutableOrganizer::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_organizer_data_with_a_url()
    {
        $organizerData = [
            '@id' => 'https://io.uitdatabank.be/organizer/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Organizer',
            '@context' => '/contexts/organizer',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
            ],
            'url' => 'https://www.publiq.be',
        ];

        $expected = new ImmutableOrganizer(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ),
            new Url('https://www.publiq.be')
        );

        $actual = $this->denormalizer->denormalize($organizerData, ImmutableOrganizer::class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_should_denormalize_organizer_data_with_title_translations()
    {
        $organizerData = [
            '@id' => 'https://io.uitdatabank.be/organizer/9f34efc7-a528-4ea8-a53e-a183f21abbab',
            '@type' => 'Organizer',
            '@context' => '/contexts/organizer',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Titel voorbeeld',
                'en' => 'Example title',
                'fr' => 'Titre de l\'exemple',
            ],
            'url' => 'https://www.publiq.be',
        ];

        $expected = new ImmutableOrganizer(
            new UUID('9f34efc7-a528-4ea8-a53e-a183f21abbab'),
            new Language('nl'),
            (new TranslatedTitle(
                new Language('nl'),
                new Title('Titel voorbeeld')
            ))
                ->withTranslation(new Language('en'), new Title('Example title'))
                ->withTranslation(new Language('fr'), new Title('Titre de l\'exemple')),
            new Url('https://www.publiq.be')
        );

        $actual = $this->denormalizer->denormalize($organizerData, ImmutableOrganizer::class);

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
        $this->denormalizer->denormalize(new \stdClass(), ImmutableOrganizer::class);
    }

    /**
     * @test
     */
    public function it_should_support_denormalization_to_immutable_organizer()
    {
        $this->assertTrue(
            $this->denormalizer->supportsDenormalization([], ImmutableOrganizer::class)
        );
    }
}
