<?php
namespace CleanRegex\Replace\Callback;

use CleanRegex\Exception\Preg\PatternReplaceException;
use CleanRegex\Internal\Pattern;
use SafeRegex\preg;

class ReplacePatternCallbackInvoker
{
    /** @var Pattern */
    private $pattern;
    /** @var string */
    private $subject;

    public function __construct(Pattern $pattern, string $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    public function invoke(callable $callback): string
    {
        $result = $this->performReplaceCallback($callback);

        if ($result === null) {
            throw new PatternReplaceException();
        }

        return $result;
    }

    private function performReplaceCallback(callable $callback): string
    {
        $object = new ReplaceCallbackObject($callback, $this->subject, $this->analyzePattern());

        return preg::replace_callback($this->pattern->pattern, $object->getCallback(), $this->subject);
    }

    private function analyzePattern(): array
    {
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);

        return $matches;
    }
}
