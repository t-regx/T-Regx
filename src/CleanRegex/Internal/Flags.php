<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

class Flags
{
    /** @var string */
    private $flags;

    public function __construct(string $flags)
    {
        $this->flags = $flags;
    }

    public static function parse(string $string): array
    {
        $segments = \explode('-', $string);
        $constructiveSegment = \array_shift($segments);
        return [new Flags($constructiveSegment), new Flags(\join('', $segments))];
    }

    public function remove(string $flags): Flags
    {
        return new Flags(\join('', \array_diff(\str_split($this->flags), \str_split($flags))));
    }

    public function append(string $flags): Flags
    {
        return new Flags(\join('', \array_merge(\str_split($this->flags), \str_split($flags))));
    }

    public function has(string $flag): bool
    {
        if (\mb_strlen($flag) === 1) {
            return \mb_strpos($this->flags, $flag) > -1;
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    public function __toString(): string
    {
        return \join('', \array_unique(\str_split($this->flags)));
    }
}
