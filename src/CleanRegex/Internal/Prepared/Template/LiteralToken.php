<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\UnconjugatedPharse;
use TRegx\CleanRegex\Internal\Prepared\Word\TextWord;
use TRegx\CleanRegex\Internal\Type\Type;
use TRegx\CleanRegex\Internal\Type\ValueType;

class LiteralToken implements Token
{
    use DelimiterAgnostic;

    /** @var string */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function phrase(): Phrase
    {
        return new UnconjugatedPharse(new TextWord($this->text));
    }

    public function type(): Type
    {
        return new ValueType($this->text);
    }
}
