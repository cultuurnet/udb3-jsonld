<?php

namespace CultuurNet\UDB3\Model\Serializer\Event;

use CultuurNet\UDB3\Model\Event\EventIDParser;
use CultuurNet\UDB3\Model\Event\ImmutableEvent;
use CultuurNet\UDB3\Model\Place\PlaceIDParser;
use CultuurNet\UDB3\Model\Place\PlaceReference;
use CultuurNet\UDB3\Model\Validation\Event\EventValidator;
use CultuurNet\UDB3\Model\ValueObject\Calendar\DateRange;
use CultuurNet\UDB3\Model\ValueObject\Calendar\DateRanges;
use CultuurNet\UDB3\Model\ValueObject\Calendar\MultipleDateRangesCalendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Day;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Days;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Hour;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Minute;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHour;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Time;
use CultuurNet\UDB3\Model\ValueObject\Calendar\PeriodicCalendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\PermanentCalendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\SingleDateRangeCalendar;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Category;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryDomain;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryLabel;
use CultuurNet\UDB3\Model\ValueObject\Text\Title;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use CultuurNet\UDB3\Model\ValueObject\Web\Url;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EventDenormalizer implements DenormalizerInterface
{
    /**
     * @var EventValidator
     */
    private $eventValidator;

    /**
     * @var EventIDParser
     */
    private $eventIDParser;

    /**
     * @var PlaceIDParser
     */
    private $placeIDParser;

    public function __construct(
        EventValidator $eventValidator = null,
        EventIDParser $eventIDParser = null,
        PlaceIDParser $placeIDParser = null
    ) {
        if (!$eventValidator) {
            $eventValidator = new EventValidator();
        }

        if (!$eventIDParser) {
            $eventIDParser = new EventIDParser();
        }

        if (!$placeIDParser) {
            $placeIDParser = new PlaceIDParser();
        }

        $this->eventValidator = $eventValidator;
        $this->eventIDParser = $eventIDParser;
        $this->placeIDParser = $placeIDParser;
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

        $title = new TranslatedTitle($mainLanguage, new Title($data['name'][$mainLanguageKey]));
        foreach ($data['name'] as $languageCode => $titleTranslation) {
            if ($languageCode == $mainLanguageKey) {
                continue;
            }

            $title = $title->withTranslation(
                new Language($languageCode),
                new Title($titleTranslation)
            );
        }

        $openingHours = array_map(
            function ($openingHourData) {
                $days = array_map(
                    function ($day) {
                        return new Day($day);
                    },
                    $openingHourData['dayOfWeek']
                );
                $days = new Days(...$days);

                $opensDateTime = \DateTimeImmutable::createFromFormat('H:i', $openingHourData['opens']);
                $opensHour = new Hour((int) $opensDateTime->format('H'));
                $opensMinute = new Minute((int) $opensDateTime->format('i'));
                $opens = new Time($opensHour, $opensMinute);

                $closesDateTime = \DateTimeImmutable::createFromFormat('H:i', $openingHourData['closes']);
                $closesHour = new Hour((int) $closesDateTime->format('H'));
                $closesMinute = new Minute((int) $closesDateTime->format('i'));
                $closes = new Time($closesHour, $closesMinute);

                return new OpeningHour($days, $opens, $closes);
            },
            isset($data['openingHours']) ? $data['openingHours'] : []
        );
        $openingHours = new OpeningHours(...$openingHours);

        switch($data['calendarType']) {
            case 'single':
                $startDate = \DateTimeImmutable::createFromFormat(\DATE_ATOM, $data['startDate']);
                $endDate = \DateTimeImmutable::createFromFormat(\DATE_ATOM, $data['endDate']);
                $dateRange = new DateRange(
                    $startDate,
                    $endDate
                );
                $calendar = new SingleDateRangeCalendar($dateRange);
                break;

            case 'multiple':
                $ranges = array_map(
                    function ($subEvent) {
                        $startDate = \DateTimeImmutable::createFromFormat(\DATE_ATOM, $subEvent['startDate']);
                        $endDate = \DateTimeImmutable::createFromFormat(\DATE_ATOM, $subEvent['endDate']);
                        return new DateRange(
                            $startDate,
                            $endDate
                        );
                    },
                    $data['subEvent']
                );
                $ranges = new DateRanges(...$ranges);
                $calendar = new MultipleDateRangesCalendar($ranges);
                break;

            case 'periodic':
                $startDate = \DateTimeImmutable::createFromFormat(\DATE_ATOM, $data['startDate']);
                $endDate = \DateTimeImmutable::createFromFormat(\DATE_ATOM, $data['endDate']);
                $dateRange = new DateRange(
                    $startDate,
                    $endDate
                );
                $calendar = new PeriodicCalendar($dateRange, $openingHours);
                break;

            case 'permanent':
            default:
                $calendar = new PermanentCalendar($openingHours);
                break;
        }

        $placeIdUrl = new Url($data['location']['@id']);
        $placeId = $this->placeIDParser->fromUrl($placeIdUrl);
        $placeReference = PlaceReference::createWithPlaceId($placeId);

        $categories = array_map(
            function ($category) {
                $id = new CategoryID($category['id']);
                $label = isset($category['label']) ? new CategoryLabel($category['label']) : null;
                $domain = isset($category['domain']) ? new CategoryDomain($category['domain']) : null;
                return new Category($id, $label, $domain);
            },
            $data['terms']
        );
        $categories = new Categories(...$categories);

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
        return $type === ImmutableEvent::class;
    }
}
