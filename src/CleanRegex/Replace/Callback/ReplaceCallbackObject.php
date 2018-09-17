<?php
namespace CleanRegex\Replace\Callback;

use CleanRegex\Exception\CleanRegex\InvalidReplacementException;
use CleanRegex\Match\Details\ReplaceMatch;

class ReplaceCallbackObject
{
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
    /** @var int */
    private $limit;

    public function __construct(callable $callback, string $subject, array $analyzedPattern, int $limit)
    {
        $this->callback = $callback;
        $this->subject = $subject;
        $this->analyzedPattern = $analyzedPattern;
        $this->limit = $limit;
    }

    public function getCallback(): callable
    {
        return function (array $match) {
            return $this->invoke($match);
        };
    }

    private function invoke(array $match): string
    {
        $replacement = call_user_func($this->callback, $this->createMatchObject());
        $this->validateReplacement($replacement);
        $this->modifyOffset($replacement, $match[0]);

        return $replacement;
    }

    private function createMatchObject(): ReplaceMatch
    {
        return new ReplaceMatch(
            $this->subject,
            $this->counter++,
            $this->analyzedPattern,
            $this->offsetModification,
            $this->limit
        );
    }

    /**
     * @param mixed $replacement
     */
    private function validateReplacement($replacement): void
    {
        if (!is_string($replacement)) {
            throw new InvalidReplacementException($replacement);
        }
    }

    private function modifyOffset(string $replacement, string $search): void
    {
        $this->offsetModification += strlen($replacement) - strlen($search);
    }
}
