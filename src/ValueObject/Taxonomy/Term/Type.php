<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term;

class Type extends Category
{
    public function __construct(CategoryID $id, CategoryLabel $label)
    {
        $domain = new CategoryDomain('eventtype');
        parent::__construct($id, $label, $domain);
    }
}
