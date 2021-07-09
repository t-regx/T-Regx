<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Internal\InternalPattern;

class Identity implements Expression
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(InternalPattern $pattern)
    {
        $this->pattern = $pattern;
    }

    public function definition(): InternalPattern
    {
        return $this->pattern;
    }
}
