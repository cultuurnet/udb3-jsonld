<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term;

use PHPUnit\Framework\TestCase;

class ThemeTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_always_return_eventtype_as_the_category_domain()
    {
        $theme = new Theme(
            new CategoryID('0.50.1.0.3'),
            new CategoryLabel('blues')
        );

        $expected = new CategoryDomain('theme');
        $actual = $theme->getDomain();

        $this->assertEquals($expected, $actual);
    }
}
