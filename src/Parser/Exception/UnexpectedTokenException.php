<?php

namespace Ehimen\Jaslang\Parser\Exception;

class UnexpectedTokenException extends SyntaxErrorException
{
    private $token;
    
    public function __construct($input, $token)
    {
        parent::__construct($input);
        
        $this->token = $token;
    }
}