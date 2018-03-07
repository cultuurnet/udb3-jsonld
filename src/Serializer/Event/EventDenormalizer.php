<?php

namespace CultuurNet\UDB3\Model\Serializer\Event;

use CultuurNet\UDB3\Model\Event\EventIDParser;
use CultuurNet\UDB3\Model\Event\ImmutableEvent;
use CultuurNet\UDB3\Model\Validation\Event\EventValidator;
use CultuurNet\UDB3\Model\ValueObject\Calendar\DateRange;
use CultuurNet\UDB3\Model\ValueObject\Calendar\SingleDateRangeCalendar;
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
    private $validator;

    /**
     * @var EventIDParser
     */
    private $idParser;

    public function __construct(
        EventValidator $eventValidator = null,
        EventIDParser $eventIDParser = null
    ) {
        if (!$eventValidator) {
            $eventValidator = new EventValidator();
        }

        if (!$eventIDParser) {
            $eventIDParser = new EventIDParser();
        }

        $this->validator = $eventValidator;
        $this->idParser = $eventIDParser;
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

        $this->validator->assert($data);

        $idUrl = new Url($data['@id']);
        $id = $this->idParser->fromUrl($idUrl);

        $mainLanguageKey = $data['mainLanguage'];
        $mainLanguage = new Language($mainLanguageKey);

        $title = new TranslatedTitle($mainLanguage, $data['title'][$mainLanguageKey]);
        foreach ($data['title'] as $languageCode => $titleTranslation) {
            if ($languageCode == $mainLanguageKey) {
                continue;
            }

            $title = $title->withTranslation(
                new Language($languageCode),
                new Title($titleTranslation)
            );
        }

        switch($data['calendarType']) {
            case 'single':
                $startDate = \DateTimeImmutable::createFromFormat(\DATE_ATOM, $data['startDate']);
                $endDate = \DateTimeImmutable::createFromFormat(\DATE_ATOM, $data['startDate']);
                $dateRange = new DateRange(
                    $startDate,
                    $endDate
                );
                $calendar = new SingleDateRangeCalendar($dateRange);
                break;

            case 'multiple':
                $ranges = [];
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === ImmutableEvent::class;
    }
}
