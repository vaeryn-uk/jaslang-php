<?php

namespace Ehimen\Jaslang\Engine\FuncDef;

use Ehimen\Jaslang\Engine\Evaluator\Context\EvaluationContext;
use Ehimen\Jaslang\Engine\FuncDef\Arg\Parameter;
use Ehimen\Jaslang\Engine\FuncDef\Arg\ArgList;

interface FuncDef
{
    /**
     * Gets the definition of parameters that this function expects.
     *
     * @return Parameter[]
     */
    public function getParameters();
    
    public function invoke(ArgList $args, EvaluationContext $context);
    
    // TODO: return values!
}
