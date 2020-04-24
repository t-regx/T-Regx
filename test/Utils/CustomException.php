<?php
namespace Test\Utils;

class CustomException extends \Exception
{
    public function __construct(string $message, $parameter = null)
    {
        parent::__construct($message);
        if ($parameter !== null) {
            throw new \AssertionError("Exception was not supposed to be given parameter: " . var_export($parameter, true));
        }
    }
}
