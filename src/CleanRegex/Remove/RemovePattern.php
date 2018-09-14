<?php
namespace CleanRegex\Remove;

use CleanRegex\Internal\Pattern;
use SafeRegex\preg;

class RemovePattern
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

    public function remove(): string
    {
        return preg::replace($this->pattern->pattern, '', $this->subject, $this->limit);
    }
}
