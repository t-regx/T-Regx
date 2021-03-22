<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quotable;

use TRegx\SafeRegex\preg;

class UserInputQuotable implements Quotable
{
    /** @var string */
    private $userInput;

    public function __construct(string $userInput)
    {
        $this->userInput = $userInput;
    }

    public function quote(string $delimiter): string
    {
        return Extended::quote(preg::quote($this->userInput, $delimiter));
    }
}
