<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\MatchAllResults;

class GroupDetails
{
    /** @var null|string */
    public $name;
    /** @var int */
    public $index;
    /** @var string|int */
    public $nameOrIndex;
    /** @var MatchAllResults */
    public $matchAll;

    public function __construct(?string $name, int $index, $nameOrIndex, MatchAllResults $matchAll)
    {
        $this->name = $name;
        $this->index = $index;
        $this->nameOrIndex = $nameOrIndex;
        $this->matchAll = $matchAll;
    }
}
