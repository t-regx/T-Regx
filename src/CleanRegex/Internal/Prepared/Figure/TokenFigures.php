<?php
namespace TRegx\CleanRegex\Internal\Prepared\Figure;

use TRegx\CleanRegex\Internal\Prepared\Template\NullToken;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;

class TokenFigures implements CountedFigures
{
    /** @var Token[] */
    private $tokens;

    public function __construct(array $tokens)
    {
        $this->tokens = \array_slice($tokens, 0);
    }

    public function nextToken(): Token
    {
        $key = \key($this->tokens);
        if ($key === null) {
            return new NullToken();
        }
        $value = \current($this->tokens);
        \next($this->tokens);
        return $value;
    }

    public function count(): int
    {
        return \count($this->tokens);
    }
}
