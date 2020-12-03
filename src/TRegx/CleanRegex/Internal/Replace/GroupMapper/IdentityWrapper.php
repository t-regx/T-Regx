<?php
namespace TRegx\CleanRegex\Internal\Replace\GroupMapper;

use TRegx\CleanRegex\Match\Details\Detail;

class IdentityWrapper implements Wrapper
{
    public function wrap(Wrappable $wrappable, Detail $initialDetail): ?string
    {
        return $wrappable->apply($initialDetail);
    }
}
