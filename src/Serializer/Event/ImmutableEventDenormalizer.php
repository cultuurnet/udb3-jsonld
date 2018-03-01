<?php

namespace CultuurNet\UDB3\Model\Serializer\Event;

use CultuurNet\UDB3\Model\Event\ImmutableEvent;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ImmutableEventDenormalizer implements DenormalizerInterface
{
    /**
     * @inheritdoc
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (!$this->supportsDenormalization($data, $class, $format)) {
            $self = self::class;
            throw new UnsupportedException("{$class} is not supported by {$self}.");
        }

        if (!is_array($data)) {
            throw new UnsupportedException('Given data should be an array.');
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
