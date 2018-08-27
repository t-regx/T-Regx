<?php
namespace CleanRegex\Replace\Callback;

use CleanRegex\Internal\Pattern;
use SafeRegex\preg;

class ReplacePatternCallbackInvoker
{
    /** @var Pattern */
    private $pattern;
    /** @var string */
    private $subject;
    /** @var int */
    private $limit;

    public function __construct(Pattern $pattern, string $subject, int $limit)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
    }

    public function invoke(callable $callback): string
    {
        return preg::replace_callback(
            $this->pattern->pattern,
            $this->getObjectCallback($callback),
            $this->subject,
            $this->limit);
    }

    private function getObjectCallback(callable $callback): callable
    {
        $object = new ReplaceCallbackObject($callback, $this->subject, $this->analyzePattern(), $this->limit);
        return $object->getCallback();
    }

    private function analyzePattern(): array
    {
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);

        return $matches;
    }
}
