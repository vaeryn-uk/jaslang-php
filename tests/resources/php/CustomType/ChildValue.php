<?php

namespace Ehimen\JaslangTestResources\CustomType;

use Ehimen\Jaslang\Engine\Value\Value;

class ChildValue implements Value
{
    public function toString()
    {
        return 'test-value';
    }
}
