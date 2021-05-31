<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;

interface Condition
{
    public function met(EntitySequence $entities): bool;

    public function commit(): void;
}
