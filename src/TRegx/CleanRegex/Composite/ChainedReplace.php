<?php
namespace TRegx\CleanRegex\Composite;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\SafeRegex\preg;

class ChainedReplace
{
    /** @var Pattern[] */
    private $patterns;
    /** @var string */
    private $subject;

    public function __construct(array $patterns, string $subject)
    {
        $this->patterns = $patterns;
        $this->subject = $subject;
    }

    public function with(string $replacement): string
    {
        $subject = $this->subject;
        foreach ($this->patterns as $pattern) {
            $subject = preg::replace($pattern, $replacement, $subject);
        }
        return $subject;
    }

    public function callback(callable $callback): string
    {
        $subject = $this->subject;
        foreach ($this->patterns as $pattern) {
            $subject = preg::replace_callback($pattern, $callback, $subject);
        }
        return $subject;
    }
}
