<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\CleanRegex\Internal\Model\GroupAware;

class AtLeastCountingStrategy implements CountingStrategy
{
    /** @var int */
    private $minimum;

    public function __construct(int $minimum)
    {
        $this->minimum = $minimum;
    }

    public function count(int $replaced, GroupAware $groupAware): void
    {
        if ($replaced < $this->minimum) {
            throw ReplacementExpectationFailedException::insufficient($replaced, $this->minimum, 'at least');
        }
    }
}
