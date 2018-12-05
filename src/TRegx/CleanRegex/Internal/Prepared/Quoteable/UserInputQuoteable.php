<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quoteable;

use TRegx\SafeRegex\preg;

class UserInputQuoteable implements Quoteable
{
    /** @var string */
    private $userInput;

    public function __construct(string $userInput)
    {
        $this->userInput = $userInput;
    }

    public function quote(string $delimiter): string
    {
        return preg::quote($this->userInput, $delimiter);
    }
}
