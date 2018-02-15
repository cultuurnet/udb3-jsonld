<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term;

use PHPUnit\Framework\TestCase;

class FacilityTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_always_return_facility_as_the_category_domain()
    {
        $facility = new Facility(
            new CategoryID('0.100.4.0.0'),
            new CategoryLabel('wheelchair accessible')
        );

        $expected = new CategoryDomain('facility');
        $actual = $facility->getDomain();

        $this->assertEquals($expected, $actual);
    }
}
