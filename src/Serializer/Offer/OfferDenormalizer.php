<?php

namespace CultuurNet\UDB3\Model\Serializer\Offer;

use CultuurNet\UDB3\Model\Offer\ImmutableOffer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Calendar\CalendarDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Taxonomy\Category\CategoriesDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Text\TranslatedDescriptionDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Text\TranslatedTitleDenormalizer;
use CultuurNet\UDB3\Model\ValueObject\Calendar\Calendar;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUIDParser;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedDescription;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use CultuurNet\UDB3\Model\ValueObject\Web\Url;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

abstract class OfferDenormalizer implements DenormalizerInterface
{
    /**
     * @var UUIDParser
     */
    private $idParser;

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
    private $categoriesDenormalizer;

    /**
     * @param UUIDParser $idParser
     * @param DenormalizerInterface|null $titleDenormalizer
     * @param DenormalizerInterface|null $calendarDenormalizer
     * @param DenormalizerInterface|null $categoriesDenormalizer
     */
    public function __construct(
        UUIDParser $idParser,
        DenormalizerInterface $titleDenormalizer = null,
        DenormalizerInterface $calendarDenormalizer = null,
        DenormalizerInterface $categoriesDenormalizer = null
    ) {
        if (!$titleDenormalizer) {
            $titleDenormalizer = new TranslatedTitleDenormalizer();
        }

        if (!$calendarDenormalizer) {
            $calendarDenormalizer = new CalendarDenormalizer();
        }

        if (!$categoriesDenormalizer) {
            $categoriesDenormalizer = new CategoriesDenormalizer();
        }

        $this->idParser = $idParser;
        $this->titleDenormalizer = $titleDenormalizer;
        $this->calendarDenormalizer = $calendarDenormalizer;
        $this->categoriesDenormalizer = $categoriesDenormalizer;
    }

    /**
     * @param array $originalData
     * @param UUID $id
     * @param Language $mainLanguage
     * @param TranslatedTitle $title
     * @param Calendar $calendar
     * @param Categories $categories
     * @return ImmutableOffer
     */
    abstract protected function createOffer(
        array $originalData,
        UUID $id,
        Language $mainLanguage,
        TranslatedTitle $title,
        Calendar $calendar,
        Categories $categories
    );

    protected function denormalizeOffer(array $data)
    {
        $idUrl = new Url($data['@id']);
        $id = $this->idParser->fromUrl($idUrl);

        $mainLanguageKey = $data['mainLanguage'];
        $mainLanguage = new Language($mainLanguageKey);

        /* @var TranslatedTitle $title */
        $title = $this->titleDenormalizer->denormalize(
            $data['name'],
            TranslatedTitle::class,
            null,
            ['originalLanguage' => $mainLanguageKey]
        );

        $calendar = $this->calendarDenormalizer->denormalize($data, Calendar::class);
        $categories = $this->categoriesDenormalizer->denormalize($data['terms'], Categories::class);

        $offer = $this->createOffer($data, $id, $mainLanguage, $title, $calendar, $categories);
        $offer = $this->denormalizeAvailableFrom($data, $offer);

        return $offer;
    }

    /**
     * @param array $data
     * @param ImmutableOffer $offer
     * @return ImmutableOffer
     */
    protected function denormalizeAvailableFrom(array $data, ImmutableOffer $offer)
    {
        if (isset($data['availableFrom'])) {
            $availableFrom = \DateTimeImmutable::createFromFormat(\DATE_ATOM, $data['availableFrom']);
            $offer = $offer->withAvailableFrom($availableFrom);
        }

        return $offer;
    }
}
