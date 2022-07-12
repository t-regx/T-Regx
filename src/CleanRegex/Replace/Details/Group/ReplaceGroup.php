<?php
namespace TRegx\CleanRegex\Replace\Details\Group;

use TRegx\CleanRegex\Match\Details\Group\Group;

interface ReplaceGroup extends Group
{
    /**
     * @deprecated
     */
    public function modifiedSubject(): string;

    /**
     * @deprecated
     */
    public function modifiedOffset(): int;

    /**
     * @deprecated
     */
    public function byteModifiedOffset(): int;
}
