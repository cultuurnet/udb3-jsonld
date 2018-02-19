<?php

namespace CultuurNet\UDB3\Model\ValueObject\Contact;

use TwoDotsTwice\ValueObject\Collection\Collection;

class Urls extends Collection
{
    /**
     * @param Url[] ...$values
     */
    public function __construct(Url ...$values)
    {
        parent::__construct(...$values);
    }
}
