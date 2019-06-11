<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Match\Details\Group\ReplaceMatchGroup;
use TRegx\CleanRegex\Match\Details\ReplaceMatch;

class MatchGroupStrategy implements ReplaceCallbackArgumentStrategy
{
    /** @var string|int */
    private $nameOrIndex;

    public function __construct($nameOrIndex)
    {
        $this->nameOrIndex = $nameOrIndex;
    }

    public function mapArgument(ReplaceMatch $match): ReplaceMatchGroup
    {
        return $match->group($this->nameOrIndex);
    }
}
