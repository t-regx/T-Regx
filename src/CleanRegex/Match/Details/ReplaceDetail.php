<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Match\Details\Group\ReplaceDetailGroup;

interface ReplaceDetail extends Detail
{
    public function modifiedOffset(): int;

    public function modifiedSubject(): string;

    public function group($nameOrIndex): ReplaceDetailGroup;
}
