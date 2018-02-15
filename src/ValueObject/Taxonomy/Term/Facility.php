<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term;

class Facility extends Category
{
    public function __construct(CategoryID $id, CategoryLabel $label)
    {
        $domain = new CategoryDomain('facility');
        parent::__construct($id, $label, $domain);
    }
}
