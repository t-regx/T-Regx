<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\UserInputQuotable;
use TRegx\CleanRegex\Internal\Type;

class LiteralToken implements Token
{
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

    public function type(): string
    {
        return Type::asString($this->text);
    }
}
