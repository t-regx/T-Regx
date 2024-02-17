<?php
namespace Regex;

use Regex\Internal\ExceptionFactory;
use Regex\Internal\IdentityPosition;
use Regex\Internal\ParsedPattern;

final class PregPattern
{
    private string $pattern;

    public function __construct(string $pattern)
    {
        $parsed = new ParsedPattern($pattern);
        if ($parsed->errorMessage) {
            $factory = new ExceptionFactory($pattern, new IdentityPosition());
            throw $factory->exceptionFor($parsed->errorMessage);
        }
        $this->pattern = $pattern;
    }

    public function test(string $subject): bool
    {
        return \preg_match($this->pattern, $subject);
    }

    /**
     * @return string[]
     */
    public function search(string $subject): array
    {
        \preg_match_all($this->pattern, $subject, $matches);
        return $matches[0];
    }

    public function delimited(): string
    {
        return $this->pattern;
    }
}
