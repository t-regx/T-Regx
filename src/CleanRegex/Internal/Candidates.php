<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\UndelimitablePatternException;

class Candidates
{
    /** @var Condition */
    private $condition;
    /** @var string[] */
    private $candidates;

    public function __construct(Condition $condition)
    {
        $this->condition = $condition;
        $this->candidates = ['/', '#', '%', '~', '+', '!', '@', '_', ';', '`', '-', '=', ',', "\1"];
    }

    public function delimiter(): Delimiter
    {
        foreach ($this->candidates as $candidate) {
            if ($this->condition->suitable($candidate)) {
                return new Delimiter($candidate);
            }
        }
        throw new UndelimitablePatternException();
    }
}
