<?php
namespace TRegx\CleanRegex\Replace\NonReplaced\Map\Exception;

class MatchMessageExceptionStrategy implements MissingReplacementExceptionMessageStrategy
{
    public function create(string $value, $nameOrIndex, string $group): MissingReplacementKeyException
    {
        return MissingReplacementKeyException::create($value);
    }
}
