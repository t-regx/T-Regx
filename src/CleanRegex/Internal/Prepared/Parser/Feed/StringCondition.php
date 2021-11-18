<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

interface StringCondition
{
    public function asString(): string;

    public function commit(): void;
}
