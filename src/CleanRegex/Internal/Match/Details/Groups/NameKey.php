<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Groups;

/**
 * @implements GroupArrayKey<string>
 */
class NameKey implements GroupArrayKey
{
    public function applies($nameOrIndex): bool
    {
        return \is_string($nameOrIndex);
    }

    public function key($nameOrIndex)
    {
        return $nameOrIndex;
    }
}
