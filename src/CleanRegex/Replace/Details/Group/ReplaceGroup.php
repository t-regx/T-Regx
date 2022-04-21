<?php
namespace TRegx\CleanRegex\Replace\Details\Group;

use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Optional;

interface ReplaceGroup extends Group
{
    public function modifiedSubject(): string;

    public function modifiedOffset(): int;

    public function byteModifiedOffset(): int;

    /**
     * @deprecated
     */
    public function map(callable $mapper): Optional;
}
