<?php
namespace Test\Utils;

use Exception;

class CustomSubjectException extends Exception
{
    /** @var string */
    public $subject;

    public function __construct(string $message, string $subject)
    {
        parent::__construct($message);
        $this->subject = $subject;
    }
}
