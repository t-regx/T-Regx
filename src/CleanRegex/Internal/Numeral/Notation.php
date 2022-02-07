<?php
namespace TRegx\CleanRegex\Internal\Numeral;

interface Notation
{
    public function integer(Base $base): int;
}
