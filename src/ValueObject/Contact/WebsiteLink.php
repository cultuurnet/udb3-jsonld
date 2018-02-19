<?php

namespace CultuurNet\UDB3\Model\ValueObject\Contact;

class WebsiteLink
{
    /**
     * @var Url
     */
    private $url;

    /**
     * @var WebsiteLabel
     */
    private $label;

    /**
     * @param Url $url
     * @param WebsiteLabel $label
     */
    public function __construct(Url $url, WebsiteLabel $label)
    {
        $this->url = $url;
        $this->label = $label;
    }

    /**
     * @return Url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param Url $url
     * @return WebsiteLink
     */
    public function withUrl(Url $url)
    {
        $c = clone $this;
        $c->url = $url;
        return $c;
    }

    /**
     * @return WebsiteLabel
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param WebsiteLabel $label
     * @return WebsiteLink
     */
    public function withLabel(WebsiteLabel $label)
    {
        $c = clone $this;
        $c->label = $label;
        return $c;
    }
}
