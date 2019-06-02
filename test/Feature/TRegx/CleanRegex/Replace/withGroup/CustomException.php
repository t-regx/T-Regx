<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\withGroup;

use Exception;

class CustomException extends Exception
{
    public function __construct(string $message, string $subject)
    {
        parent::__construct($message);
    }
}
