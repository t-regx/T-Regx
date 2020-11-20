<?php
namespace TRegx\CleanRegex\Internal\Replace\GroupMapper;

use TRegx\CleanRegex\Match\Details\Detail;

class IdentityWrapper implements Wrapper
{
    public function map(GroupMapper $mapper, string $occurrence, Detail $initialDetail): ?string
    {
        return $mapper->map($occurrence, $initialDetail);
    }
}
