<?php
namespace Danon\CleanRegex\Replace;

use Danon\CleanRegex\Exception\Preg\PatternReplaceException;
use Danon\CleanRegex\Internal\Pattern;
use Danon\CleanRegex\Match\Match;
use Danon\CleanRegex\Match\ReplaceMatch;

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
        $matches = [];
        $counter = 0;

        return preg_replace_callback($this->pattern->pattern, function (array $match) use (&$callback, &$matches, &$counter) {
            $matches = $this->mergePreservingKeys($matches ?: $match, $match);

            return call_user_func($callback, new ReplaceMatch($this->subject, $counter++, $matches, $this->pattern));
        }, $this->subject);
    }

    function mergePreservingKeys(array $matches, array $match): array
    {
        array_walk($matches, function (&$value, $key, $newMatch) {
            $value = [
                [1 => -1] + (array)$value,
                [$newMatch[$key], -1]
            ];
        }, $match);

        return $matches;
    }
}
