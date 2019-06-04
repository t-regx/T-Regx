<?php
namespace TRegx\CleanRegex\Replace\GroupMapper;

interface GroupMapper
{
    public function map(string $occurrence): ?string;
}
