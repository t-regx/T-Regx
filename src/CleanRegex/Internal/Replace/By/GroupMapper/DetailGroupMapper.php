<?php
namespace TRegx\CleanRegex\Internal\Replace\By\GroupMapper;

interface DetailGroupMapper extends GroupMapper
{
    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void;
}
