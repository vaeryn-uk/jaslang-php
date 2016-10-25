<?php

namespace Ehimen\JaslangTestResources;

use Ehimen\Jaslang\FuncDef\ArgDef;
use Ehimen\Jaslang\Operator\Binary;
use Ehimen\Jaslang\Value;

class AndOperator extends Binary
{
    protected function getLeftArgType()
    {
        return ArgDef::BOOLEAN;
    }

    protected function getRightArgType()
    {
        return ArgDef::BOOLEAN;
    }

    protected function performOperation(Value\Value $left, Value\Value $right)
    {
        /** @var Value\Boolean $left */
        /** @var Value\Boolean $right */
        return new Value\Boolean($left->getValue() && $right->getValue());
    }
}