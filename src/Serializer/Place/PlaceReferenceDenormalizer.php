<?php

namespace CultuurNet\UDB3\Model\Serializer\Place;

use CultuurNet\UDB3\Model\Place\PlaceIDParser;
use CultuurNet\UDB3\Model\Place\PlaceReference;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUIDParser;
use CultuurNet\UDB3\Model\ValueObject\Web\Url;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class PlaceReferenceDenormalizer implements DenormalizerInterface
{
    /**
     * @var PlaceIDParser
     */
    private $placeIDParser;

    /**
     * @param UUIDParser|null $placeIDParser
     */
    public function __construct(UUIDParser $placeIDParser = null)
    {
        if (!$placeIDParser) {
            $placeIDParser = new PlaceIDParser();
        }

        $this->placeIDParser = $placeIDParser;
    }

    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (!$this->supportsDenormalization($data, $class, $format)) {
            throw new UnsupportedException("PlaceReferenceDenormalizer does not support {$class}.");
        }

        if (!is_array($data)) {
            throw new UnsupportedException('Location data should be an associative array.');
        }

        // @todo Check for embedded place and include it.
        $placeIdUrl = new Url($data['location']['@id']);
        $placeId = $this->placeIDParser->fromUrl($placeIdUrl);
        return PlaceReference::createWithPlaceId($placeId);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === PlaceReference::class;
    }
}
