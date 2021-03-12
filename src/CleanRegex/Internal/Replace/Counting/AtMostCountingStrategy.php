<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\SafeRegex\preg;

class AtMostCountingStrategy implements CountingStrategy
{
    /** @var InternalPattern */
    private $pattern;
    /** @var string */
    private $subject;
    /** @var int */
    private $limit;
    /** @var string */
    private $limitPhrase;

    public function __construct(InternalPattern $pattern, string $subject, int $limit, string $phrase)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->limitPhrase = $phrase;
    }

    public function count(int $replaced): void
    {
        preg::replace($this->pattern->pattern, '', $this->subject, $this->limit + 1, $realCount);
        if ($realCount > $this->limit) {
            throw ReplacementExpectationFailedException::superfluous($realCount, $this->limit, $this->limitPhrase);
        }
    }
}
