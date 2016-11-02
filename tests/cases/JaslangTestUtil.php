<?php

namespace Ehimen\JaslangTests;

use Ehimen\Jaslang\Engine\Lexer\Token;

trait JaslangTestUtil
{
    public function createToken($type, $value, $position)
    {
        return new Token($value, $type, $position);
    }
}