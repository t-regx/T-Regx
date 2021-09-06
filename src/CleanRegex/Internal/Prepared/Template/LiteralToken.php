<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\UserInputQuotable;
use TRegx\CleanRegex\Internal\Type;
use TRegx\CleanRegex\Internal\ValueType;

class LiteralToken implements Token
{
    use DelimiterAgnostic;

    /** @var string */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function formatAsQuotable(): Quotable
    {
        return new UserInputQuotable($this->text);
    }

    public function type(): Type
    {
        return new ValueType($this->text);
    }
}
