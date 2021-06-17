<?php
namespace TRegx\CleanRegex\Internal\Prepared\Figure;

interface CountedFigures extends Figures
{
    public function count(): int;
}
