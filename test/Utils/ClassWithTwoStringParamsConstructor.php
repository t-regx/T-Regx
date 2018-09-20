<?php
namespace Test\Utils;

use Exception;

class ClassWithTwoStringParamsConstructor extends Exception
{
    /** @var string */
    private $subject;

    public function __construct(string $message, string $subject)
    {
        parent::__construct($message);
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }
}
