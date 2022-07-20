<?php
namespace TRegx\CleanRegex\Replace\Details\Group;

/**
 * @deprecated Use {@see Group} instead.
 */
interface ReplaceGroup extends \TRegx\CleanRegex\Match\Group
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
