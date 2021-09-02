<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\UserInputQuotable;

class Literal implements Expression
{
    use StrictInterpretation;

    /** @var string */
    private $text;
    /** @var string */
    private $flags;

    public function __construct(string $text, string $flags)
    {
        $this->text = $text;
        $this->flags = $flags;
    }

    protected function quotable(): Quotable
    {
        return new UserInputQuotable($this->text);
    }

    protected function delimiter(): Delimiter
    {
        return new Delimiter('/');
    }

    protected function flags(): Flags
    {
        return new Flags($this->flags);
    }

    protected function undevelopedInput(): string
    {
        return $this->text;
    }
}
