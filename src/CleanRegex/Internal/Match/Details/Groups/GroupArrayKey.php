<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Groups;

interface GroupArrayKey
{
    public function applies($nameOrIndex): bool;

    public function key($nameOrIndex);
}
