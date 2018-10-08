<?php
namespace TRegx\CleanRegex\Internal\Factory\Group;

use TRegx\CleanRegex\Match\Details\Group\MatchAll;

class GroupDetails
{
    /** @var null|string */
    public $name;
    /** @var int */
    public $index;
    /** @var string|int */
    public $nameOrIndex;
    /** @var MatchAll */
    public $matchAll;

    public function __construct(?string $name, int $index, $nameOrIndex, MatchAll $matchAll)
    {
        $this->name = $name;
        $this->index = $index;
        $this->nameOrIndex = $nameOrIndex;
        $this->matchAll = $matchAll;
    }
}
