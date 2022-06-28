<?php
namespace TRegx\CleanRegex\Internal\Prepared\Figure;

use TRegx\CleanRegex\Internal\Prepared\Template\Token;

interface CountedFigures
{
    public function nextToken(): Token;

    public function count(): int;
}
