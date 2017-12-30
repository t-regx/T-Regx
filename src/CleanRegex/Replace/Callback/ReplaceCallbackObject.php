<?php
namespace CleanRegex\Replace\Callback;

use CleanRegex\Match\ReplaceMatch;

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

    public function __construct(callable $callback, string $subject, array $analyzedPattern)
    {
        $this->callback = $callback;
        $this->subject = $subject;
        $this->analyzedPattern = $analyzedPattern;
    }

    public function invoke(array $match): string
    {
        $replacement = call_user_func($this->callback, $this->createMatchObject());

        $this->modifyOffset($replacement, $match[0]);

        return $replacement;
    }

    private function createMatchObject(): ReplaceMatch
    {
        return new ReplaceMatch(
            $this->subject,
            $this->counter++,
            $this->analyzedPattern,
            $this->offsetModification
        );
    }

    public function modifyOffset(string $replacement, string $search): void
    {
        $this->offsetModification += strlen($replacement) - strlen($search);
    }

    public function getCallback(): callable
    {
        return [$this, 'invoke'];
    }
}
