<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Term;

use PHPUnit\Framework\TestCase;

class FacilitiesTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_filter_duplicate_facilities()
    {
        $facilities = [
            new Facility(
                new CategoryID('0.100.4.0.0'),
                new CategoryLabel('wheelchair accessible')
            ),
            new Facility(
                new CategoryID('0.102.3.0.0'),
                new CategoryLabel('audio guide')
            ),
            new Facility(
                new CategoryID('0.100.4.0.0'),
                new CategoryLabel('wheelchair accessible')
            ),
        ];

        $expected = [
            new Facility(
                new CategoryID('0.100.4.0.0'),
                new CategoryLabel('wheelchair accessible')
            ),
            new Facility(
                new CategoryID('0.102.3.0.0'),
                new CategoryLabel('audio guide')
            ),
        ];

        $collection = new Facilities(...$facilities);
        $actual = $collection->toArray();

        $this->assertEquals($expected, $actual);
    }
}
