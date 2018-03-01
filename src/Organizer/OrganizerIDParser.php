<?php

namespace CultuurNet\UDB3\Model\Organizer;

use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUIDParser;
use CultuurNet\UDB3\Model\ValueObject\Web\Url;

class OrganizerIDParser implements UUIDParser
{
    // @codingStandardsIgnoreStart
    const REGEX = '/\\/organizer[s]?\\/([0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12})[\\/]?/';
    // @codingStandardsIgnoreEnd

    /**
     * @inheritdoc
     */
    public function fromUrl(Url $url)
    {
        $url = $url->toString();

        $matches = [];
        preg_match(self::REGEX, $url, $matches);

        if (count($matches) > 0) {
            return new UUID($matches[0]);
        } else {
            throw new \InvalidArgumentException('No OrganizerID found in given Url.');
        }
    }
}
