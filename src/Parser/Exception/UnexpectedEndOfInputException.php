<?php

namespace Ehimen\Jaslang\Parser\Exception;

class UnexpectedEndOfInputException extends SyntaxErrorException  
{
    public function __construct($input)
    {
        parent::__construct($input, 'Unexpected end of input');
    }
}