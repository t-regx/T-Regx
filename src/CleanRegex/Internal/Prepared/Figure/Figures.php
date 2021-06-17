<?php
namespace TRegx\CleanRegex\Internal\Prepared\Figure;

use TRegx\CleanRegex\Internal\Prepared\Template\Token;

interface Figures
{
    public function nextToken(): Token;
}
