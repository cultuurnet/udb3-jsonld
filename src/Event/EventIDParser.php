<?php

namespace CultuurNet\UDB3\Model\Event;

use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUIDParser;
use CultuurNet\UDB3\Model\ValueObject\Web\Url;

class EventIDParser implements UUIDParser
{
    // @codingStandardsIgnoreStart
    const REGEX = '/\\/event[s]?\\/([0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12})[\\/]?/';
    // @codingStandardsIgnoreEnd

    /**
     * @inheritdoc
     */
    public function fromUrl(Url $url)
    {
        $url = $url->toString();

        $matches = [];
        preg_match(self::REGEX, $url, $matches);

        if (count($matches) > 1) {
            return new UUID($matches[1]);
        } else {
            throw new \InvalidArgumentException('No EventID found in given Url.');
        }
    }
}
