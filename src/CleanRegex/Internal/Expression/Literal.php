<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Word\TextWord;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;

class Literal implements Expression
{
    use StrictInterpretation;

    /** @var string */
    private $text;
    /** @var Flags */
    private $flags;

    public function __construct(string $text, string $flags)
    {
        $this->text = $text;
        $this->flags = new Flags($flags);
    }

    protected function word(): Word
    {
        return new TextWord($this->text);
    }

    protected function delimiter(): Delimiter
    {
        return new Delimiter('/');
    }

    protected function flags(): Flags
    {
        return $this->flags;
    }

    protected function undevelopedInput(): string
    {
        return $this->text;
    }
}
