<?php
namespace TRegx\CleanRegex\Internal\Replace\By\GroupMapper;

use TRegx\CleanRegex\Match\Details\Detail;

interface GroupMapper
{
    public function map(string $occurrence, Detail $initialDetail): ?string;
}
