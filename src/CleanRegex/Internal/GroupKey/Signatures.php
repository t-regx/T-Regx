<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

interface Signatures
{
    public function signature(GroupKey $group): GroupSignature;
}
