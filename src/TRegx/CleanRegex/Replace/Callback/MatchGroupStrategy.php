<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Match\Details\Group\ReplaceMatchGroup;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;

class MatchGroupStrategy implements ReplaceCallbackArgumentStrategy
{
    /** @var string|int */
    private $nameOrIndex;

    public function __construct($nameOrIndex)
    {
        $this->nameOrIndex = $nameOrIndex;
    }

    public function mapArgument(ReplaceDetail $match): ReplaceMatchGroup
    {
        return $match->group($this->nameOrIndex);
    }
}
