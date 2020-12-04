<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

class IndexedRawMatchOffset extends RawMatchOffset
{
    /** @var int */
    private $index;

    public function __construct(array $match, int $index)
    {
        parent::__construct($match);
        $this->index = $index;
    }

    public function getIndex(): int
    {
        return $this->index;
    }
}
