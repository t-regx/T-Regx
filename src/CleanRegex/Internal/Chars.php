<?php
namespace TRegx\CleanRegex\Internal;

/**
 * I would name this class "String", but I can't
 * in PHP7. When we resign from PHP 7 compatibility,
 * we should rename this class to "String".
 */
class Chars
{
    /** @var string */
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function contains(string $infix): bool
    {
        if ($infix === '') {
            return true;
        }
        return \mb_substr($this->string, \mb_strpos($this->string, $infix), \mb_strlen($infix)) === $infix;
    }

    public function startsWith(string $prefix): bool
    {
        return \mb_substr($this->string, 0, \mb_strlen($prefix)) === $prefix;
    }

    public function endsWith(string $suffix): bool
    {
        $suffixLength = \mb_strlen($suffix);
        return \mb_substr($this->string, -$suffixLength, $suffixLength) === $suffix;
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
