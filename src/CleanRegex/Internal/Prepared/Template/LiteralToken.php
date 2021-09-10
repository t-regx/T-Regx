<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Word\TextWord;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;
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

    public function formatAsQuotable(): Word
    {
        return new TextWord($this->text);
    }

    public function type(): Type
    {
        return new ValueType($this->text);
    }
}
