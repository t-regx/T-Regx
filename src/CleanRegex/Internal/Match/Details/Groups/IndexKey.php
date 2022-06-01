<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Groups;

class IndexKey implements GroupArrayKey
{
    public function applies($nameOrIndex): bool
    {
        if ($nameOrIndex === 0) {
            return false;
        }
        return \is_int($nameOrIndex);
    }

    public function key($nameOrIndex): int
    {
        return $nameOrIndex - 1;
    }
}
