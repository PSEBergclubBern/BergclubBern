<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 23.03.2017
 * Time: 14:59
 */

namespace BergclubPlugin\MVC\Exceptions;


use Exception;

class NotABergClubUserException extends \Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}