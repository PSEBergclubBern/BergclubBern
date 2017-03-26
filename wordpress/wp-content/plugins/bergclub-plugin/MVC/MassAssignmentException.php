<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 26.03.2017
 * Time: 15:59
 */

namespace BergclubPlugin\MVC;


use Exception;

class MassAssignmentException extends \Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}