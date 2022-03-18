<?php
namespace TRegx\CleanRegex\Internal\Match\Numeral;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\Numeral\Base;

interface IntegerExceptions
{
    public function formatException(Base $base, string $numeral): IntegerFormatException;

    public function overflowException(Base $base, string $numeral): IntegerOverflowException;
}
