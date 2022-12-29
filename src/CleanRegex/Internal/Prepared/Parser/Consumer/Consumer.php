<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;

interface Consumer
{
    public function consume(EntitySequence $entities): void;
}
