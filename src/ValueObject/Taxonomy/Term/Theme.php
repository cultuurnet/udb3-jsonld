<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term;

class Theme extends Category
{
    public function __construct(CategoryID $id, CategoryLabel $label)
    {
        $domain = new CategoryDomain('theme');
        parent::__construct($id, $label, $domain);
    }
}
