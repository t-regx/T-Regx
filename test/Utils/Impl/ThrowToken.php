<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;
use TRegx\CleanRegex\Internal\Type;

class ThrowToken implements Token
{
    public function suitable(string $candidate): bool
    {
        throw new \AssertionError("Token wasn't supposed to be used");
    }

    public function formatAsQuotable(): Quotable
    {
        throw new \AssertionError("Token wasn't supposed to be used");
    }

    public function type(): Type
    {
        throw new \AssertionError("Token wasn't supposed to be used");
    }
}
