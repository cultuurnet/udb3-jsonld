<?php

namespace CultuurNet\UDB3\Model\Serializer\Place;

use CultuurNet\UDB3\Model\Place\Place;
use CultuurNet\UDB3\Model\Place\PlaceIDParser;
use CultuurNet\UDB3\Model\Place\ImmutablePlace;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Calendar\CalendarDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Geography\TranslatedAddressDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Taxonomy\Category\CategoriesDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Text\TranslatedTitleDenormalizer;
use CultuurNet\UDB3\Model\Validation\Place\PlaceValidator;
use CultuurNet\UDB3\Model\ValueObject\Calendar\Calendar;
use CultuurNet\UDB3\Model\ValueObject\Geography\TranslatedAddress;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUIDParser;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use CultuurNet\UDB3\Model\ValueObject\Web\Url;
use Respect\Validation\Validator;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class PlaceDenormalizer implements DenormalizerInterface
{
    /**
     * @var Validator
     */
    private $placeValidator;

    /**
     * @var UUIDParser
     */
    private $placeIDParser;

    /**
     * @var DenormalizerInterface
     */
    private $titleDenormalizer;

    /**
     * @var DenormalizerInterface
     */
    private $calendarDenormalizer;

    /**
     * @var DenormalizerInterface
     */
    private $addressDenormalizer;

    /**
     * @var DenormalizerInterface
     */
    private $categoriesDenormalizer;

    public function __construct(
        Validator $placeValidator = null,
        UUIDParser $placeIDParser = null,
        DenormalizerInterface $titleDenormalizer = null,
        DenormalizerInterface $calendarDenormalizer = null,
        DenormalizerInterface $addressDenormalizer = null,
        DenormalizerInterface $categoriesDenormalizer = null
    ) {
        if (!$placeValidator) {
            $placeValidator = new PlaceValidator();
        }

        if (!$placeIDParser) {
            $placeIDParser = new PlaceIDParser();
        }

        if (!$titleDenormalizer) {
            $titleDenormalizer = new TranslatedTitleDenormalizer();
        }

        if (!$calendarDenormalizer) {
            $calendarDenormalizer = new CalendarDenormalizer();
        }

        if (!$addressDenormalizer) {
            $addressDenormalizer = new TranslatedAddressDenormalizer();
        }

        if (!$categoriesDenormalizer) {
            $categoriesDenormalizer = new CategoriesDenormalizer();
        }

        $this->placeValidator = $placeValidator;
        $this->placeIDParser = $placeIDParser;
        $this->titleDenormalizer = $titleDenormalizer;
        $this->calendarDenormalizer = $calendarDenormalizer;
        $this->addressDenormalizer = $addressDenormalizer;
        $this->categoriesDenormalizer = $categoriesDenormalizer;
    }

    /**
     * @inheritdoc
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (!$this->supportsDenormalization($data, $class, $format)) {
            throw new UnsupportedException("PlaceDenormalizer does not support {$class}.");
        }

        if (!is_array($data)) {
            throw new UnsupportedException('Place data should be an associative array.');
        }

        $this->placeValidator->assert($data);

        $idUrl = new Url($data['@id']);
        $id = $this->placeIDParser->fromUrl($idUrl);

        $mainLanguageKey = $data['mainLanguage'];
        $mainLanguage = new Language($mainLanguageKey);

        /* @var TranslatedTitle $title */
        $title = $this->titleDenormalizer->denormalize(
            $data['name'],
            TranslatedTitle::class,
            null,
            ['originalLanguage' => $mainLanguageKey]
        );

        /* @var TranslatedAddress $address */
        $address = $this->addressDenormalizer->denormalize(
            $data['address'],
            TranslatedAddress::class,
            null,
            ['originalLanguage' => $mainLanguageKey]
        );

        $calendar = $this->calendarDenormalizer->denormalize($data, Calendar::class);
        $categories = $this->categoriesDenormalizer->denormalize($data['terms'], Categories::class);

        $place = new ImmutablePlace(
            $id,
            $mainLanguage,
            $title,
            $calendar,
            $address,
            $categories
        );

        if (isset($data['availableFrom'])) {
            $availableFrom = \DateTimeImmutable::createFromFormat(\DATE_ATOM, $data['availableFrom']);
            $place = $place->withAvailableFrom($availableFrom);
        }

        return $place;
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === ImmutablePlace::class || $type === Place::class;
    }
}
