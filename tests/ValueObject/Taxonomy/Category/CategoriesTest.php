<?php

namespace CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category;

use PHPUnit\Framework\TestCase;

class CategoriesTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_never_be_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Array should not be empty.');

        new Categories();
    }

    /**
     * @test
     */
    public function it_should_filter_duplicate_terms()
    {
        $terms = [
            new Category(
                new CategoryID('0.100.4.0.0'),
                new CategoryLabel('wheelchair accessible'),
                new CategoryDomain('facility')
            ),
            new Category(
                new CategoryID('0.50.4.0.0'),
                new CategoryLabel('concert'),
                new CategoryDomain('eventtype')
            ),
            new Category(
                new CategoryID('0.100.4.0.0'),
                new CategoryLabel('wheelchair accessible'),
                new CategoryDomain('facility')
            ),
        ];

        $expected = [
            new Category(
                new CategoryID('0.100.4.0.0'),
                new CategoryLabel('wheelchair accessible'),
                new CategoryDomain('facility')
            ),
            new Category(
                new CategoryID('0.50.4.0.0'),
                new CategoryLabel('concert'),
                new CategoryDomain('eventtype')
            ),
        ];

        $collection = new Categories(...$terms);
        $actual = $collection->toArray();

        $this->assertEquals($expected, $actual);
    }
}
