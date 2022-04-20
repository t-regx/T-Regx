<?php
namespace TRegx\CleanRegex\Replace\Details\Group;

use Throwable;
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
    public function orThrow(Throwable $throwable = null);

    /**
     * @deprecated
     */
    public function orReturn($substitute);

    /**
     * @deprecated
     */
    public function orElse(callable $substituteProducer);

    /**
     * @deprecated
     */
    public function map(callable $mapper): Optional;
}
