<?php
namespace TRegx\CleanRegex\Replace\Map\Exception;

interface MissingReplacementExceptionMessageStrategy
{
    public function create(string $value, $nameOrIndex): MissingReplacementKeyException;
}
