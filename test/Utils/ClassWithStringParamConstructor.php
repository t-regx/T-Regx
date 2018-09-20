<?php
namespace Test\Utils;

use Exception;

class ClassWithStringParamConstructor extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
