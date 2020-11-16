<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Match\Details\Group\ReplaceDetailGroup;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;

class MatchGroupStrategy implements ReplaceCallbackArgumentStrategy
{
    /** @var string|int */
    private $nameOrIndex;

    public function __construct($nameOrIndex)
    {
        $this->nameOrIndex = $nameOrIndex;
    }

    public function mapArgument(ReplaceDetail $match): ReplaceDetailGroup
    {
        return $match->group($this->nameOrIndex);
    }
}
