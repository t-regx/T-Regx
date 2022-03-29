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

    public function endsWith(string $suffix): bool
    {
        if ($this->string === '') {
            return false;
        }
        return $this->string[-1] === $suffix;
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
