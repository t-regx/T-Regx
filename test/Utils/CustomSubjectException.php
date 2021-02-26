<?php
namespace Test\Utils;

use Exception;

class CustomSubjectException extends Exception
{
    /** @var string */
    public $subject;

    public function __construct(string $message, string $subject, $parameter = null)
    {
        parent::__construct($message);
        $this->subject = $subject;
        if ($parameter !== null) {
            throw new \AssertionError("Exception was not supposed to be given parameter: " . var_export($parameter, true));
        }
    }
}
