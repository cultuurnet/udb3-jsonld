<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term;

use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_always_return_eventtype_as_the_category_domain()
    {
        $type = new Type(
            new CategoryID('0.50.1.0.0'),
            new CategoryLabel('concert')
        );

        $expected = new CategoryDomain('eventtype');
        $actual = $type->getDomain();

        $this->assertEquals($expected, $actual);
    }
}
