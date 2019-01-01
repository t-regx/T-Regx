<?php
namespace TRegx\CleanRegex\Replace\Map\Exception;

class MatchMessageExceptionStrategy implements MissingReplacementExceptionMessageStrategy
{
    public function create(string $value, $nameOrIndex): MissingReplacementKeyException
    {
        return MissingReplacementKeyException::create($value);
    }
}
