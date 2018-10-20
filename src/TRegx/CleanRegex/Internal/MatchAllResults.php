<?php
namespace TRegx\CleanRegex\Internal;

use function array_map;
use TRegx\CleanRegex\Internal\Model\RawMatchesInterface;
use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;

class MatchAllResults
{
    /** @var RawMatchesInterface */
    private $matches;
    /** @var string|int */
    private $group;

    public function __construct(RawMatchesInterface $matches, $group)
    {
        $this->matches = $matches;
        $this->group = $group;
    }

    /**
     * @return (string|null)[]
     */
    public function all(): array
    {
        return $this->matches->getGroupTexts($this->group);
    }
}
