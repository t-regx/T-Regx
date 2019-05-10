<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Match\Details\Group\ReplaceMatchGroup;

interface ReplaceMatch extends Match
{
    public function modifiedOffset(): int;

    public function modifiedSubject(): string;

    public function group($nameOrIndex): ReplaceMatchGroup;
}
