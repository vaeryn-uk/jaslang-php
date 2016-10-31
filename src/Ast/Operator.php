<?php

namespace Ehimen\Jaslang\Ast;

use Ehimen\Jaslang\FuncDef\OperatorSignature;

class Operator extends UnlimitedChildrenParentNode
{
    private $operator;

    private $signature;
    
    public function __construct($operator, OperatorSignature $signature)
    {
        $this->operator = $operator;
        $this->signature = $signature;
        parent::__construct([]);
    }

    public function getOperator()
    {
        return $this->operator;
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public function canBeClosed()
    {
        // TODO: should be exactly equal? Should to isValid() or something?
        return count($this->getChildren()) >= $this->getExpectedArgCount();
    }

    public function debug()
    {
        $leftParts  = [];
        $rightParts = [];

        foreach ($this->getChildren() as $i => $child) {
            if ($i >= $this->getSignature()->getLeftArgs()) {
                $rightParts[] = $child->debug();
            } else {
                $leftParts[] = $child->debug();
            }
        }

        return sprintf(
            '%s%s%s%s%s',
            implode(' ', $leftParts),
            empty($leftParts) ? '' : ' ',
            $this->operator,
            empty($rightParts) ? '' : ' ',
            implode(' ', $rightParts)
        );
    }

    /**
     * @return int
     */
    private function getExpectedArgCount()
    {
        return $this->signature->getLeftArgs() + $this->signature->getRightArgs();
    }
}
