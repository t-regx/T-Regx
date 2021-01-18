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
    /** @var string */
    private $verb;

    public function __construct(Pattern $pattern, string $subject, int $limit, string $verb)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->verb = $verb;
    }

    public function count(int $replaced): void
    {
        if ($replaced < $this->limit) {
            throw ReplacementExpectationFailedException::insufficient($this->verb, $this->limit, $replaced);
        }
    }
}
