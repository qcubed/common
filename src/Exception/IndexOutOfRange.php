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
 * Thrown when trying to access an element in an array whose index does not exist
 * NOTE: this exception will not fire automatically for you unless you use it with the try-catch block
 * @was QIndexOutOfRangeException
 */
class IndexOutOfRange extends Caller
{
    /**
     * Constructor method
     * @param string $intIndex
     * @param int    $strMessage
     */
    public function __construct($intIndex, $strMessage)
    {
        if ($strMessage) {
            $strMessage = ": " . $strMessage;
        }
        parent::__construct(sprintf(Translator::translate("Index (%s) is out of range%s"), $intIndex, $strMessage), 2);
    }
}
