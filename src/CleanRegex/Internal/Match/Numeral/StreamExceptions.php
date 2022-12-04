<?php
namespace TRegx\CleanRegex\Internal\Match\Numeral;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\Numeral\Base;

class StreamExceptions implements IntegerExceptions
{
    public function formatException(Base $base, string $numeral): IntegerFormatException
    {
        return new IntegerFormatException("Expected to parse stream element '$numeral', but it is not a valid integer in base $base");
    }

    public function overflowException(Base $base, string $numeral): IntegerOverflowException
    {
        return IntegerOverflowException::forStream($numeral, $base);
    }
}
