<?php

namespace CultuurNet\UDB3\Model\ValueObject\Identity;

use CultuurNet\UDB3\Model\ValueObject\Web\Url;

class RegexUUIDParser implements UUIDParser
{
    /**
     * @var string
     */
    private $regex;

    /**
     * @var string
     */
    private $idName;

    /**
     * @param string $regex
     * @param string $idName;
     */
    public function __construct($regex, $idName = 'ID')
    {
        $this->regex = $regex;
        $this->idName = $idName;
    }

    /**
     * @inheritdoc
     */
    public function fromUrl(Url $url)
    {
        $url = $url->toString();

        $matches = [];
        preg_match($this->regex, $url, $matches);

        if (count($matches) > 1) {
            return new UUID($matches[1]);
        } else {
            throw new \InvalidArgumentException("No {$this->idName} found in given Url.");
        }
    }
}
