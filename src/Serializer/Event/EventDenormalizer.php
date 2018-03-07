<?php

namespace CultuurNet\UDB3\Model\Serializer\Event;

use CultuurNet\UDB3\Model\Event\Event;
use CultuurNet\UDB3\Model\Event\EventIDParser;
use CultuurNet\UDB3\Model\Event\ImmutableEvent;
use CultuurNet\UDB3\Model\Place\PlaceReference;
use CultuurNet\UDB3\Model\Serializer\Place\PlaceReferenceDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Calendar\CalendarDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Taxonomy\Category\CategoriesDenormalizer;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Text\TranslatedTitleDenormalizer;
use CultuurNet\UDB3\Model\Validation\Event\EventValidator;
use CultuurNet\UDB3\Model\ValueObject\Calendar\Calendar;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUIDParser;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use CultuurNet\UDB3\Model\ValueObject\Web\Url;
use Respect\Validation\Validator;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EventDenormalizer implements DenormalizerInterface
{
    /**
     * @var Validator
     */
    private $eventValidator;

    /**
     * @var UUIDParser
     */
    private $eventIDParser;

    /**
     * @var DenormalizerInterface
     */
    private $titleDenormalizer;

    /**
     * @var DenormalizerInterface
     */
    private $placeReferenceDenormalizer;

    /**
     * @var DenormalizerInterface
     */
    private $calendarDenormalizer;

    /**
     * @var DenormalizerInterface
     */
    private $categoriesDenormalizer;

    public function __construct(
        Validator $eventValidator = null,
        UUIDParser $eventIDParser = null,
        DenormalizerInterface $titleDenormalizer = null,
        DenormalizerInterface $placeReferenceDenormalizer = null,
        DenormalizerInterface $calendarDenormalizer = null,
        DenormalizerInterface $categoriesDenormalizer = null
    ) {
        if (!$eventValidator) {
            $eventValidator = new EventValidator();
        }

        if (!$eventIDParser) {
            $eventIDParser = new EventIDParser();
        }

        if (!$titleDenormalizer) {
            $titleDenormalizer = new TranslatedTitleDenormalizer();
        }

        if (!$placeReferenceDenormalizer) {
            $placeReferenceDenormalizer = new PlaceReferenceDenormalizer();
        }

        if (!$calendarDenormalizer) {
            $calendarDenormalizer = new CalendarDenormalizer();
        }

        if (!$categoriesDenormalizer) {
            $categoriesDenormalizer = new CategoriesDenormalizer();
        }

        $this->eventValidator = $eventValidator;
        $this->eventIDParser = $eventIDParser;
        $this->titleDenormalizer = $titleDenormalizer;
        $this->placeReferenceDenormalizer = $placeReferenceDenormalizer;
        $this->calendarDenormalizer = $calendarDenormalizer;
        $this->categoriesDenormalizer = $categoriesDenormalizer;
    }

    /**
     * @inheritdoc
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (!$this->supportsDenormalization($data, $class, $format)) {
            throw new UnsupportedException("EventDenormalizer does not support {$class}.");
        }

        if (!is_array($data)) {
            throw new UnsupportedException('Event data should be an associative array.');
        }

        $this->eventValidator->assert($data);

        $idUrl = new Url($data['@id']);
        $id = $this->eventIDParser->fromUrl($idUrl);

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
        $placeReference = $this->placeReferenceDenormalizer->denormalize($data, PlaceReference::class);
        $categories = $this->categoriesDenormalizer->denormalize($data['terms'], Categories::class);

        return new ImmutableEvent(
            $id,
            $mainLanguage,
            $title,
            $calendar,
            $placeReference,
            $categories
        );
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === ImmutableEvent::class || $type === Event::class;
    }
}
