<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Label;

use TwoDotsTwice\ValueObject\Collection\Behaviour\HasUniqueValues;
use TwoDotsTwice\ValueObject\Collection\Collection;

class Labels extends Collection
{
    use HasUniqueValues;

    /**
     * @param Label[] ...$labels
     */
    public function __construct(Label ...$labels)
    {
        $labelNames = array_map(
            function (Label $label) {
                return $label->getName();
            },
            $labels
        );

        $this->guardUniqueValues($labelNames);

        parent::__construct(...$labels);
    }
}
