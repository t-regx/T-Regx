<?php
namespace TRegx\CleanRegex\ForArray;

interface ForArrayPattern
{
    public function filter(): array;

    public function filterAssoc(): array;

    public function filterByKeys(): array;
}
