<?php

use TRegx\CleanRegex\Internal\VisibleCharacters;

function format(string $string, array $arguments): string
{
    $argumentsCount = \count($arguments);
    $result = \preg_replace_callback('/\{}/', function () use (&$arguments): string {
        return new VisibleCharacters(\array_pop($arguments));
    }, $string, -1, $replaced);
    if ($argumentsCount === $replaced) {
        return $result;
    }
    throw new \Exception("Mismatched format arguments count");
}
