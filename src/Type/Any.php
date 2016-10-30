<?php

namespace Ehimen\Jaslang\Type;

class Any implements Type
{
    /**
     * {@inheritdoc}
     * 
     * Any is our base type. It does not extend anything.
     */
    public function getParent()
    {
        return null;
    }

    public function isA(Type $type)
    {
        return ($this instanceof $type);
    }
}