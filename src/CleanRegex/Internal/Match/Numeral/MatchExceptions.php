<?php
namespace TRegx\CleanRegex\Internal\Match\Numeral;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\Numeral\Base;

class MatchExceptions implements IntegerExceptions
{
    public function formatException(Base $base, string $numeral): IntegerFormatException
    {
        return IntegerFormatException::forMatch($numeral, $base);
    }

    public function overflowException(Base $base, string $numeral): IntegerOverflowException
    {
        return IntegerOverflowException::forMatch($numeral, $base);
    }
}
