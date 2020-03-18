<?php
namespace Test\Utils;

use Exception;

class CustomSubjectException extends Exception
{
    public function __construct(string $message, string $subject)
    {
        parent::__construct($message);
    }
}
