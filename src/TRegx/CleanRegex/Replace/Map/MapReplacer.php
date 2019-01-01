<?php
namespace TRegx\CleanRegex\Replace\Map;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\StringValue;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\SafeRegex\preg;
use function array_key_exists;
use function is_string;

class MapReplacer
{
    /** @var Pattern */
    private $pattern;
    /** @var Subjectable */
    private $subject;
    /** @var int */
    private $limit;

    public function __construct(Pattern $pattern, Subjectable $subject, int $limit)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
    }

    public function mapOrCallHandler($nameOrIndex, array $map, callable $unexpectedReplacementHandler): string
    {
        $this->validateMap($map);
        return $this->replaceUsingCallback(function (array $match) use ($nameOrIndex, $map, $unexpectedReplacementHandler) {
            return $this->getReplacementOrHandle($match, $nameOrIndex, $map, $unexpectedReplacementHandler);
        });
    }

    private function validateMap(array $map): void
    {
        foreach ($map as $occurrence => $replacement) {
            $this->validateOccurrence($occurrence);
            $this->validateReplacement($replacement);
        }
    }

    private function validateOccurrence($occurrence): void
    {
        if (!is_string($occurrence)) {
            $value = (new StringValue($occurrence))->getString();
            throw new InvalidArgumentException("Invalid replacement map key. Expected string, but $value given");
        }
    }

    private function validateReplacement($replacement): void
    {
        if (!is_string($replacement)) {
            $value = (new StringValue($replacement))->getString();
            throw new InvalidArgumentException("Invalid replacement map value. Expected string, but $value given");
        }
    }

    public function replaceUsingCallback(callable $closure): string
    {
        return preg::replace_callback($this->pattern->pattern, $closure, $this->subject->getSubject(), $this->limit);
    }

    private function getReplacementOrHandle(array $match, $nameOrIndex, array $map, callable $unexpectedReplacementHandler): string
    {
        $occurrence = $match[$nameOrIndex];
        if (array_key_exists($occurrence, $map)) {
            return $map[$occurrence];
        }
        return $unexpectedReplacementHandler($match[0], $occurrence);
    }
}
