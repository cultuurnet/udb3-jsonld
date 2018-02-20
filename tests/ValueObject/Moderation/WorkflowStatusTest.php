<?php

namespace CultuurNet\UDB3\Model\ValueObject\Moderation;

use PHPUnit\Framework\TestCase;

class WorkflowStatusTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_have_five_possible_values()
    {
        $readyForValidation = WorkflowStatus::readyforvalidation();
        $approved = WorkflowStatus::approved();
        $rejected = WorkflowStatus::rejected();
        $draft = WorkflowStatus::draft();
        $deleted = WorkflowStatus::deleted();

        $this->assertEquals('readyforvalidation', $readyForValidation->toString());
        $this->assertEquals('approved', $approved->toString());
        $this->assertEquals('rejected', $rejected->toString());
        $this->assertEquals('draft', $draft->toString());
        $this->assertEquals('deleted', $deleted->toString());
    }
}
