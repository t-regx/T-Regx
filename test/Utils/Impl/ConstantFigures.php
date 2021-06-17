<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\Prepared\Figure\CountedFigures;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;

class ConstantFigures implements CountedFigures
{
    /** @var int */
    private $count;
    /** @var Token|null */
    private $token;

    public function __construct(int $count, Token $token = null)
    {
        $this->count = $count;
        $this->token = $token;
    }

    public static function literal(string $literal): self
    {
        return new self(1, new LiteralToken($literal));
    }

    public function count(): int
    {
        return $this->count;
    }

    public function nextToken(): Token
    {
        if ($this->token === null) {
            throw new AssertionError("Failed to assert that figures didn't get nextToken().");
        }
        return $this->token;
    }
}
