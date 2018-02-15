<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term;

class Category
{
    /**
     * @var CategoryID
     */
    private $id;

    /**
     * @var CategoryLabel
     */
    private $label;

    /**
     * @var CategoryDomain
     */
    private $domain;

    /**
     * @param CategoryID $id
     * @param CategoryLabel $label
     * @param CategoryDomain $domain
     */
    public function __construct(CategoryID $id, CategoryLabel $label, CategoryDomain $domain)
    {
        $this->id = $id;
        $this->label = $label;
        $this->domain = $domain;
    }

    /**
     * @return CategoryID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return CategoryLabel
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return CategoryDomain
     */
    public function getDomain()
    {
        return $this->domain;
    }
}
