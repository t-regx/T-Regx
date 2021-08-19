<?php
namespace TRegx\CleanRegex\Internal\Number;

interface Notation
{
    public function integer(Base $base): int;
}
