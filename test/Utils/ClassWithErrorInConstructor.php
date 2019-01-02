<?php
namespace Test\Utils;

use Error;
use Exception;

class ClassWithErrorInConstructor extends Exception
{
    public function __construct()
    {
        parent::__construct();
        throw new Error();
    }
}
