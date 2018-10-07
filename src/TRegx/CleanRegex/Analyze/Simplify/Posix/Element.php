<?php
namespace TRegx\CleanRegex\Analyze\Simplify\Posix;

interface Element
{
    public function get(): string;

    public function contains(Element $element): bool;
}
