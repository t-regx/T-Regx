<?php
namespace Regex;

use Regex\Internal\ExceptionFactory;
use Regex\Internal\IdentityPosition;

final class PregPattern extends Internal\CompiledPattern implements Regex
{
    public function __construct(string $pattern)
    {
        parent::__construct($pattern,
            new ExceptionFactory($pattern, new IdentityPosition()));
    }
}
