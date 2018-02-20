<?php

namespace CultuurNet\UDB3\Model\ValueObject\Moderation;

use TwoDotsTwice\ValueObject\String\Enum;

/**
 * @method static WorkflowStatus readyforvalidation()
 * @method static WorkflowStatus approved()
 * @method static WorkflowStatus rejected()
 * @method static WorkflowStatus draft()
 * @method static WorkflowStatus deleted()
 */
class WorkflowStatus extends Enum
{
    /**
     * @inheritdoc
     */
    protected function getAllowedValues()
    {
        return [
            'readyforvalidation',
            'approved',
            'rejected',
            'draft',
            'deleted',
        ];
    }
}
