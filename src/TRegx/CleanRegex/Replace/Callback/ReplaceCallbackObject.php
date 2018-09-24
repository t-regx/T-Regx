<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Exception\CleanRegex\InvalidReplacementException;
use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\ReplaceMatch;
use function call_user_func;
use function is_string;
use function strlen;
use function substr_replace;

class ReplaceCallbackObject
{
    private const GROUP_WHOLE_MATCH = 0;

    /** @var callable */
    private $callback;
    /** @var string */
    private $subject;
    /** @var array */
    private $analyzedPattern;

    /** @var int */
    private $counter = 0;
    /** @var int */
    private $offsetModification = 0;
    /** @var string */
    private $subjectModification;
    /** @var int */
    private $limit;

    public function __construct(callable $callback, string $subject, array $analyzedPattern, int $limit)
    {
        $this->callback = $callback;
        $this->subject = $subject;
        $this->analyzedPattern = $analyzedPattern;
        $this->limit = $limit;
        $this->subjectModification = $this->subject;
    }

    public function getCallback(): callable
    {
        return function (array $match) {
            return $this->invoke($match);
        };
    }

    private function invoke(array $match): string
    {
        $result = call_user_func($this->callback, $this->createMatchObject());
        $replacement = $this->getReplacement($result);
        $this->modifySubject($replacement);
        $this->modifyOffset($match[0], $replacement);
        return $replacement;
    }

    private function createMatchObject(): ReplaceMatch
    {
        return new ReplaceMatch(
            $this->subject,
            $this->counter++,
            $this->analyzedPattern,
            $this->offsetModification,
            $this->subjectModification,
            $this->limit
        );
    }

    private function getReplacement($result): string
    {
        $replacement = $this->stringifyMatchGroup($result);
        if (is_string($replacement)) {
            return $replacement;
        }
        throw new InvalidReplacementException($result);
    }

    private function stringifyMatchGroup($replacement)
    {
        if ($replacement instanceof MatchGroup) {
            return $replacement->text();
        }
        return $replacement;
    }

    private function modifyOffset(string $search, string $replacement): void
    {
        $this->offsetModification += mb_strlen($replacement) - mb_strlen($search);
    }

    private function modifySubject(string $replacement): void
    {
        list($value, $offset) = $this->analyzedPattern[self::GROUP_WHOLE_MATCH][$this->counter - 1];

        $this->subjectModification = substr_replace(
            $this->subjectModification,
            $replacement,
            $this->getReplaceStart($offset),
            strlen($value)
        );
    }

    private function getReplaceStart($offset): int
    {
        return ByteOffset::normalize($this->subject, $offset) + $this->offsetModification;
    }
}
