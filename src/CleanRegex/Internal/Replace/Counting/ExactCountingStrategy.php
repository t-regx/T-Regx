<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;

class ExactCountingStrategy implements CountingStrategy
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

    public function count(int $replaced): void
    {
        if ($replaced < $this->limit) {
            throw ReplacementExpectationFailedException::insufficient($this->limit, $replaced);
        }
        \preg_replace($this->pattern->pattern, '', $this->subject, $this->limit + 1, $realCount);
        if ($realCount > $this->limit) {
            throw ReplacementExpectationFailedException::superfluous($this->limit, $realCount);
        }
    }
}
