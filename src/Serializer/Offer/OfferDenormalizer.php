<?php

namespace CultuurNet\UDB3\Model\Serializer\Offer;

use CultuurNet\UDB3\Model\Offer\ImmutableOffer;
use CultuurNet\UDB3\Model\Organizer\OrganizerReference;
use CultuurNet\UDB3\Model\Serializer\Place\OrganizerReferenceDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Audience\AgeRangeDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Calendar\CalendarDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Taxonomy\Category\CategoriesDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Taxonomy\Label\LabelsDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Text\TranslatedDescriptionDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Text\TranslatedTitleDenormalizer;
use CultuurNet\UDB3\Model\ValueObject\Audience\AgeRange;
use CultuurNet\UDB3\Model\ValueObject\Calendar\Calendar;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUIDParser;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Label\Labels;
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
    private $descriptionDenormalizer;

    /**
     * @var DenormalizerInterface
     */
    private $calendarDenormalizer;

    /**
     * @var DenormalizerInterface
     */
    private $categoriesDenormalizer;

    /**
     * @var DenormalizerInterface
     */
    private $labelsDenormalizer;

    /**
     * @var DenormalizerInterface
     */
    private $organizerReferenceDenormalizer;

    /**
     * @var DenormalizerInterface
     */
    private $ageRangeDenormalizer;

    /**
     * @param UUIDParser $idParser
     * @param DenormalizerInterface|null $titleDenormalizer
     * @param DenormalizerInterface|null $descriptionDenormalizer
     * @param DenormalizerInterface|null $calendarDenormalizer
     * @param DenormalizerInterface|null $categoriesDenormalizer
     * @param DenormalizerInterface|null $labelsDenormalizer
     * @param DenormalizerInterface|null $organizerReferenceDenormalizer
     * @param DenormalizerInterface|null $ageRangeDenormalizer
     */
    public function __construct(
        UUIDParser $idParser,
        DenormalizerInterface $titleDenormalizer = null,
        DenormalizerInterface $descriptionDenormalizer = null,
        DenormalizerInterface $calendarDenormalizer = null,
        DenormalizerInterface $categoriesDenormalizer = null,
        DenormalizerInterface $labelsDenormalizer = null,
        DenormalizerInterface $organizerReferenceDenormalizer = null,
        DenormalizerInterface $ageRangeDenormalizer = null
    ) {
        if (!$titleDenormalizer) {
            $titleDenormalizer = new TranslatedTitleDenormalizer();
        }

        if (!$descriptionDenormalizer) {
            $descriptionDenormalizer = new TranslatedDescriptionDenormalizer();
        }

        if (!$calendarDenormalizer) {
            $calendarDenormalizer = new CalendarDenormalizer();
        }

        if (!$categoriesDenormalizer) {
            $categoriesDenormalizer = new CategoriesDenormalizer();
        }

        if (!$labelsDenormalizer) {
            $labelsDenormalizer = new LabelsDenormalizer();
        }

        if (!$organizerReferenceDenormalizer) {
            $organizerReferenceDenormalizer = new OrganizerReferenceDenormalizer();
        }

        if (!$ageRangeDenormalizer) {
            $ageRangeDenormalizer = new AgeRangeDenormalizer();
        }

        $this->idParser = $idParser;
        $this->titleDenormalizer = $titleDenormalizer;
        $this->descriptionDenormalizer = $descriptionDenormalizer;
        $this->calendarDenormalizer = $calendarDenormalizer;
        $this->categoriesDenormalizer = $categoriesDenormalizer;
        $this->labelsDenormalizer = $labelsDenormalizer;
        $this->organizerReferenceDenormalizer = $organizerReferenceDenormalizer;
        $this->ageRangeDenormalizer = $ageRangeDenormalizer;
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
        $offer = $this->denormalizeDescription($data, $offer);
        $offer = $this->denormalizeLabels($data, $offer);
        $offer = $this->denormalizeOrganizerReference($data, $offer);
        $offer = $this->denormalizeAgeRange($data, $offer);
        $offer = $this->denormalizeAvailableFrom($data, $offer);

        return $offer;
    }

    /**
     * @param array $data
     * @param ImmutableOffer $offer
     * @return ImmutableOffer
     */
    protected function denormalizeDescription(array $data, ImmutableOffer $offer)
    {
        if (isset($data['description'])) {
            /* @var TranslatedDescription $description */
            $description = $this->descriptionDenormalizer->denormalize(
                $data['description'],
                TranslatedDescription::class,
                null,
                ['originalLanguage' => $data['mainLanguage']]
            );

            $offer = $offer->withDescription($description);
        }

        return $offer;
    }

    /**
     * @param array $data
     * @param ImmutableOffer $offer
     * @return ImmutableOffer
     */
    protected function denormalizeLabels(array $data, ImmutableOffer $offer)
    {
        $labels = $this->labelsDenormalizer->denormalize($data, Labels::class);
        return $offer->withLabels($labels);
    }

    /**
     * @param array $data
     * @param ImmutableOffer $offer
     * @return ImmutableOffer
     */
    protected function denormalizeOrganizerReference(array $data, ImmutableOffer $offer)
    {
        if (isset($data['organizer'])) {
            $organizerReference = $this->organizerReferenceDenormalizer->denormalize(
                $data['organizer'],
                OrganizerReference::class
            );

            $offer = $offer->withOrganizerReference($organizerReference);
        }

        return $offer;
    }

    /**
     * @param array $data
     * @param ImmutableOffer $offer
     * @return ImmutableOffer
     */
    protected function denormalizeAgeRange(array $data, ImmutableOffer $offer)
    {
        if (isset($data['typicalAgeRange'])) {
            $ageRange = $this->ageRangeDenormalizer->denormalize($data['typicalAgeRange'], AgeRange::class);
            $offer = $offer->withAgeRange($ageRange);
        }

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
