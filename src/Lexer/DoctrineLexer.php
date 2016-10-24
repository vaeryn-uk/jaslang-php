<?php

namespace Ehimen\Jaslang\Lexer;

use Doctrine\Common\Lexer\AbstractLexer;
use Ehimen\Jaslang\Parser\Exception\UnexpectedEndOfInputException;

/**
 * TODO: this should wrap Doctrine lexer, not extend it.
 */
class DoctrineLexer extends AbstractLexer implements Lexer
{
    const DTYPE_OTHER = 0;
    const DTYPE_PAREN_LEFT  = 1;
    const DTYPE_PAREN_RIGHT = 2;
    const DTYPE_QUOTE_SINGLE = 3;
    const DTYPE_QUOTE_DOUBLE = 4;
    const DTYPE_BACKSLASH = 5;
    const DTYPE_COMMA = 6;
    const DTYPE_WHITESPACE = 7;
    const DTYPE_OPERATOR = 8;
    
    private $currentQuote = null;
    private $currentToken = '';
    private $currentPosition = 0;
    private $tokens = [];

    public function tokenize($input)
    {
        $this->resetState();
        $this->setInput($input);
        
        $updatePosition = true;
        
        do {
            $token = $this->peek();
            if ($updatePosition) {
                $this->currentPosition = $token['position'];
            }
            $updatePosition = true;
            $value = $token['value'];
            $type  = $token['type'];
            $inQuotes = is_string($this->currentQuote);

            // Handle escaping.
            if ($inQuotes && $value === Lexer::ESCAPE_CHAR) {
                $nextValue = $this->glimpse()['value'];
                
                if (in_array($nextValue, Lexer::ESCAPABLE_CHARS, true)) {
                    $updatePosition = false;
                    $this->currentToken .= $nextValue;
                    $this->moveNext();
                    continue;
                }
            }

            if ($this->currentQuote === $value) {
                $this->token(Lexer::TOKEN_STRING);
                continue;
            }

            if (!$inQuotes && ((static::DTYPE_QUOTE_DOUBLE === $type) || (static::DTYPE_QUOTE_SINGLE === $type))) {
                $this->currentQuote = $value;
                $updatePosition = false;
                continue;
            }

            $this->currentToken .= $value;

            if (!$inQuotes) {
                if ($type === static::DTYPE_PAREN_LEFT) {
                    $this->token(Lexer::TOKEN_LEFT_PAREN);
                } elseif ($type === static::DTYPE_PAREN_RIGHT) {
                    $this->token(Lexer::TOKEN_RIGHT_PAREN);
                } elseif ($type === static::DTYPE_COMMA) {
                    $this->token(Lexer::TOKEN_COMMA);
                } elseif ($type === static::DTYPE_WHITESPACE) {
                    $this->token(Lexer::TOKEN_WHITESPACE);
                } elseif ($type === static::DTYPE_BACKSLASH) {
                    $this->token(Lexer::TOKEN_BACKSLASH);
                } elseif ($type === static::DTYPE_OPERATOR) {
                    $this->token(Lexer::TOKEN_OPERATOR);
                } elseif (ctype_alpha($value[0])) {     // If starting with a letter, it's an identifier.
                    $lower = strtolower($value);
                    
                    // Our doctrine lexer doesn't distinguish between bool/identifier, check now.
                    if (('false' === $lower) || ('true' === $value)) {
                        $this->token(Lexer::TOKEN_BOOLEAN);
                    }
                    
                    $this->token(Lexer::TOKEN_IDENTIFIER);
                } elseif (is_numeric($value)) {
                    $this->token(Lexer::TOKEN_NUMBER);
                } else {
                    $this->token(Lexer::TOKEN_UNKNOWN);
                }
            } else {
                // We're continuing a token, so leave the position marker where it was.
                $updatePosition = false;
            }
        } while ($this->moveNext());
        
        
        if (!empty($inQuotes)) {
            throw new UnexpectedEndOfInputException($input);
        }
        
        return $this->tokens;
    }

    private function resetState()
    {
        $this->currentQuote = null;
        $this->currentToken = '';
        $this->currentPosition = 0;
        $this->tokens = [];
    }

    private function token($type)
    {
        if (strlen($this->currentToken) === 0) {
            return;
        }
        
        $this->tokens[] = [
            'type'     => $type,
            'value'    => $this->currentToken,
            'position' => $this->currentPosition + 1, // Doctrine position is 0-indexed, we want first char to be at 1.
        ];
        
        $this->currentToken = '';
        $this->currentQuote = null;
    }

    protected function getCatchablePatterns()
    {
        return [
            '[+-]?\d+(?:\.\d*)?',   // Decimal representation.
            '[+-\/\*=!^<>]+', // Operators
            '\w+',        // Group all word characters
            '\s+',        // And group all continuous whitespace
        ];
    }

    protected function getNonCatchablePatterns()
    {
        return [];
    }

    protected function getType(&$value)
    {
        if ('\\' === $value) {
            return static::DTYPE_BACKSLASH;
        } elseif ('(' === $value) {
            return static::DTYPE_PAREN_LEFT;
        } elseif (')' === $value) {
            return static::DTYPE_PAREN_RIGHT;
        } elseif ('\'' === $value) {
            return static::DTYPE_QUOTE_SINGLE;
        } elseif ('"' === $value) {
            return static::DTYPE_QUOTE_DOUBLE;
        } elseif (',' === $value) {
            return static::DTYPE_COMMA;
        } elseif ('' === trim($value)) {
            return static::DTYPE_WHITESPACE;
        } elseif (str_replace(['+', '/', '-', '*', '!', '^', '=', '<', '>'], '', $value) === '') {
            return static::DTYPE_OPERATOR;
        }
        
        return static::DTYPE_OTHER;
    }
}