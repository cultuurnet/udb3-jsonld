<?php

namespace CultuurNet\UDB3\Model\Serializer\Place;

use CultuurNet\UDB3\Model\Offer\ImmutableOffer;
use CultuurNet\UDB3\Model\Place\Place;
use CultuurNet\UDB3\Model\Place\PlaceIDParser;
use CultuurNet\UDB3\Model\Place\ImmutablePlace;
use CultuurNet\UDB3\Model\Serializer\Offer\OfferDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Calendar\CalendarDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Geography\TranslatedAddressDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Taxonomy\Category\CategoriesDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Text\TranslatedTitleDenormalizer;
use CultuurNet\UDB3\Model\Validation\Place\PlaceValidator;
use CultuurNet\UDB3\Model\ValueObject\Calendar\Calendar;
use CultuurNet\UDB3\Model\ValueObject\Geography\TranslatedAddress;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUIDParser;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use CultuurNet\UDB3\Model\ValueObject\Web\Url;
use Respect\Validation\Validator;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class PlaceDenormalizer extends OfferDenormalizer
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

        if (!$addressDenormalizer) {
            $addressDenormalizer = new TranslatedAddressDenormalizer();
        }

        $this->placeValidator = $placeValidator;
        $this->addressDenormalizer = $addressDenormalizer;

        parent::__construct(
            $placeIDParser,
            $titleDenormalizer,
            $calendarDenormalizer,
            $categoriesDenormalizer
        );
    }

    /**
     * @inheritdoc
     */
    protected function createOffer(
        array $originalData,
        UUID $id,
        Language $mainLanguage,
        TranslatedTitle $title,
        Calendar $calendar,
        Categories $categories
    ) {
        /* @var TranslatedAddress $address */
        $address = $this->addressDenormalizer->denormalize(
            $originalData['address'],
            TranslatedAddress::class,
            null,
            ['originalLanguage' => $originalData['mainLanguage']]
        );

        return new ImmutablePlace(
            $id,
            $mainLanguage,
            $title,
            $calendar,
            $address,
            $categories
        );
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

        return $this->denormalizeOffer($data);
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === ImmutablePlace::class || $type === Place::class;
    }
}
