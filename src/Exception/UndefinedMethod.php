<?php
/**
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Exception;

use QCubed\Translator;

/**
 * Thrown when we try to call an undefined method. Helpful for codegen.
 * @was QUndefinedMethodException
 */
class UndefinedMethod extends Caller
{
    public function __construct($strClass, $strMethod)
    {
        parent::__construct(sprintf(Translator::translate("Undefined method in '%s' class: %s"), $strClass, $strMethod),
            2);
    }
}
