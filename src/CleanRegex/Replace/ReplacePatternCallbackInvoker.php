<?php
namespace CleanRegex\Replace;

use CleanRegex\Exception\Preg\PatternReplaceException;
use CleanRegex\Internal\Pattern;
use CleanRegex\Match\ReplaceMatch;
use SafeRegex\ExceptionFactory;

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
        $matches = $this->analyzePattern();
        $counter = 0;

        $offsetModification = 0;

        $return = @preg_replace_callback($this->pattern->pattern, function (array $match) use (&$callback, $matches, &$counter, &$offsetModification) {
            $search = $match[0];
            $replacement = call_user_func($callback, new ReplaceMatch($this->subject, $counter++, $matches, $this->pattern, $offsetModification));

            $offsetModification += strlen($replacement) - strlen($search);

            return $replacement;
        }, $this->subject);

        (new ExceptionFactory())->retrieveGlobalsAndThrow('preg_replace_callback', $return);

        return $return;
    }

    private function analyzePattern(): array
    {
        $matches = [];
        $result = @preg_match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);
        (new ExceptionFactory())->retrieveGlobalsAndThrow('preg_match_all', $result);

        return $matches;
    }
}
