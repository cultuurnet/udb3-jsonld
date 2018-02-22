<?php

namespace CultuurNet\UDB3\Model\ValueObject\Price;

use TwoDotsTwice\ValueObject\Collection\Collection;

class Tariffs extends Collection
{
    public function __construct(Tariff ...$tariffs)
    {
        parent::__construct(...$tariffs);
    }
}
