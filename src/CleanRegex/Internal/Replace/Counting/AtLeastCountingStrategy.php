<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;

class AtLeastCountingStrategy implements CountingStrategy
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
            throw ReplacementExpectationFailedException::insufficient('at least', $this->limit, $replaced);
        }
    }
}
