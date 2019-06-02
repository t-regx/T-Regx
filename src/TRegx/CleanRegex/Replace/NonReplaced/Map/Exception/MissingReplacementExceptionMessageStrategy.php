<?php
namespace TRegx\CleanRegex\Replace\NonReplaced\Map\Exception;

interface MissingReplacementExceptionMessageStrategy
{
    public function create(string $value, $nameOrIndex, string $group): MissingReplacementKeyException;
}
