<?php
namespace TRegx\CleanRegex\Internal\Replace\By;

use TRegx\CleanRegex\Internal\Replace\Wrappable;
use TRegx\CleanRegex\Internal\Replace\Wrapper;
use TRegx\CleanRegex\Match\Details\Detail;

class IdentityWrapper implements Wrapper
{
    public function wrap(Wrappable $wrappable, Detail $initialDetail): ?string
    {
        return $wrappable->apply($initialDetail);
    }
}
