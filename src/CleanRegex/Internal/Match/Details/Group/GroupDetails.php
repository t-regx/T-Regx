<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;

class GroupDetails
{
    /** @var null|string */
    public $name;
    /** @var int */
    public $index;
    /** @var string|int */
    public $nameOrIndex;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(?string $name, int $index, $nameOrIndex, MatchAllFactory $allFactory)
    {
        $this->name = $name;
        $this->index = $index;
        $this->nameOrIndex = $nameOrIndex;
        $this->allFactory = $allFactory;
    }

    public function all(): array
    {
        return \array_values($this->allFactory->getRawMatches()->getGroupTexts($this->index));
    }
}
