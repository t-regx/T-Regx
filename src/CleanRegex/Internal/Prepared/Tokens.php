<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Internal\Condition;
use TRegx\CleanRegex\Internal\Prepared\Figure\CountedFigures;
use TRegx\CleanRegex\Internal\Prepared\Figure\TokenFigures;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;

class Tokens
{
    /** @var Token[] */
    private $tokens;

    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    public function next(Token $token): Tokens
    {
        return new Tokens(\array_merge($this->tokens, [$token]));
    }

    public function condition(): Condition
    {
        return new CompositeCondition($this->tokens);
    }

    public function figures(): CountedFigures
    {
        return new TokenFigures($this->tokens);
    }
}
