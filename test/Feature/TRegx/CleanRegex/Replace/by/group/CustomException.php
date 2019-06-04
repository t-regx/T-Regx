<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group;

use Exception;

class CustomException extends Exception
{
    public function __construct(string $message, string $subject)
    {
        parent::__construct($message);
    }
}
