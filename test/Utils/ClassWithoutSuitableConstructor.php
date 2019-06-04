<?php
namespace Test\Utils;

use Exception;

class ClassWithoutSuitableConstructor extends Exception
{
    public function __construct(int $a)
    {
        parent::__construct('');
    }
}
