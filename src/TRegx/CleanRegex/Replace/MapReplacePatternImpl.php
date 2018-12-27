<?php
namespace TRegx\CleanRegex\Replace;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\StringValue;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Replace\Exception\MissingReplacementKeyException;
use TRegx\SafeRegex\preg;
use function array_key_exists;
use function is_string;

class MapReplacePatternImpl implements MapReplacePattern
{
    /** @var Pattern */
    private $pattern;
    /** @var Subjectable */
    private $subject;
    /** @var int */
    private $limit;
    /** @var string|int */
    private $group;

    public function __construct(Pattern $pattern, Subjectable $subject, int $limit, $group)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->group = $group;
    }

    public function group($nameOrIndex): MapGroupReplacePattern
    {
        return null;
    }

    public function map(array $occurrencesAndReplacements): string
    {
        return $this->mapOrCallHandler($occurrencesAndReplacements, function (string $occurrence) {
            throw MissingReplacementKeyException::create($occurrence);
        });
    }

    public function mapIfExists(array $occurrencesAndReplacements): string
    {
        return $this->mapOrCallHandler($occurrencesAndReplacements, function (string $occurrence) {
            return $occurrence;
        });
    }

    public function mapDefault(array $occurrencesAndReplacements, string $defaultReplacement): string
    {
        return $this->mapOrCallHandler($occurrencesAndReplacements, function () use ($defaultReplacement) {
            return $defaultReplacement;
        });
    }

    private function mapOrCallHandler(array $occurrencesAndReplacements, callable $unexpectedReplacementHandler): string
    {
        $this->validateMap($occurrencesAndReplacements);
        return $this->replaceUsingCallback(function (array $match) use ($unexpectedReplacementHandler, $occurrencesAndReplacements) {
            $occurrence = $match[$this->group];
            if (array_key_exists($occurrence, $occurrencesAndReplacements)) {
                return $occurrencesAndReplacements[$occurrence];
            }
            return $unexpectedReplacementHandler($occurrence);
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
}
