<?php

namespace CultuurNet\UDB3\Model\Serializer\Organizer;

use CultuurNet\UDB3\Model\Organizer\ImmutableOrganizer;
use CultuurNet\UDB3\Model\Organizer\Organizer;
use CultuurNet\UDB3\Model\Organizer\OrganizerIDParser;
use CultuurNet\UDB3\Model\Serializer\ValueObject\Text\TranslatedTitleDenormalizer;
use CultuurNet\UDB3\Model\Validation\Organizer\OrganizerValidator;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUIDParser;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use CultuurNet\UDB3\Model\ValueObject\Web\Url;
use Respect\Validation\Validator;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class OrganizerDenormalizer implements DenormalizerInterface
{
    /**
     * @var Validator
     */
    private $organizerValidator;

    /**
     * @var UUIDParser
     */
    private $organizerIDParser;

    /**
     * @var DenormalizerInterface
     */
    private $titleDenormalizer;

    public function __construct(
        Validator $organizerValidator = null,
        UUIDParser $organizerIDParser = null,
        DenormalizerInterface $titleDenormalizer = null
    ) {
        if (!$organizerValidator) {
            $organizerValidator = new OrganizerValidator();
        }

        if (!$organizerIDParser) {
            $organizerIDParser = new OrganizerIDParser();
        }

        if (!$titleDenormalizer) {
            $titleDenormalizer = new TranslatedTitleDenormalizer();
        }

        $this->organizerValidator = $organizerValidator;
        $this->organizerIDParser = $organizerIDParser;
        $this->titleDenormalizer = $titleDenormalizer;
    }

    /**
     * @inheritdoc
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (!$this->supportsDenormalization($data, $class, $format)) {
            throw new UnsupportedException("OrganizerDenormalizer does not support {$class}.");
        }

        if (!is_array($data)) {
            throw new UnsupportedException('Organizer data should be an associative array.');
        }

        $this->organizerValidator->assert($data);

        $idUrl = new Url($data['@id']);
        $id = $this->organizerIDParser->fromUrl($idUrl);

        $mainLanguageKey = $data['mainLanguage'];
        $mainLanguage = new Language($mainLanguageKey);

        /* @var TranslatedTitle $title */
        $title = $this->titleDenormalizer->denormalize(
            $data['name'],
            TranslatedTitle::class,
            null,
            ['originalLanguage' => $mainLanguageKey]
        );

        $url = null;
        if (isset($data['url'])) {
            $url = new Url($data['url']);
        }

        return new ImmutableOrganizer(
            $id,
            $mainLanguage,
            $title,
            $url
        );
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === ImmutableOrganizer::class || $type === Organizer::class;
    }
}
