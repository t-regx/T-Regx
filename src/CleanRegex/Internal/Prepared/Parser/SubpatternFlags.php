<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

class SubpatternFlags
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
        return [new SubpatternFlags($constructiveSegment), new SubpatternFlags(\join('', $segments))];
    }

    public function remove(SubpatternFlags $flags): SubpatternFlags
    {
        return new SubpatternFlags(\join('', \array_diff(\str_split($this->flags), \str_split($flags->flags))));
    }

    public function append(SubpatternFlags $flags): SubpatternFlags
    {
        return new SubpatternFlags(\join('', \array_merge(\str_split($this->flags), \str_split($flags->flags))));
    }

    public function has(string $flag): bool
    {
        if (\mb_strlen($flag) === 1) {
            return \str_contains($this->flags, $flag);
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }
}
