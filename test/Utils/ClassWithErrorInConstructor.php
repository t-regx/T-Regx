<?php
namespace Utils;

use Error;
use Exception;

class ClassWithErrorInConstructor extends Exception
{
    public function __construct()
    {
        throw new Error();
    }
}
