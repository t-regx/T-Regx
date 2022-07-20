<?php
namespace TRegx\CleanRegex\Internal\Replace\By\GroupMapper;

use TRegx\CleanRegex\Match\Detail;

interface GroupMapper
{
    public function map(string $occurrence, Detail $initialDetail): ?string;
}
