<?php

namespace CultuurNet\UDB3\Model\Event;

use CultuurNet\UDB3\Model\Offer\Offer;
use CultuurNet\UDB3\Model\ValueObject\Audience\Audience;

interface Event extends Offer
{
    /**
     * @return Audience
     */
    public function getAudience();
}
