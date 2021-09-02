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
        return $this->quoteExtendedWhitespace(preg::quote($this->userInput, $delimiter));
    }

    private function quoteExtendedWhitespace(string $string): string
    {
        return \strtr($string, [
            ' '    => '\ ',   #32
            "\t"   => '\t',   #9
            "\n"   => '\n',   #10
            "\x0B" => '\x0B', #11
            "\f"   => '\f',   #12
            "\r"   => '\r',   #13
        ]);
    }
}
