<?php
namespace TRegx\CleanRegex\Internal\Match\Numeral;

use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Numeral\NumeralFormatException;
use TRegx\CleanRegex\Internal\Numeral\NumeralOverflowException;
use TRegx\CleanRegex\Internal\Numeral\StringNumeral;

class IntegerBase
{
    /** @var Base */
    private $base;
    /** @var IntegerExceptions */
    private $exceptions;

    public function __construct(Base $base, IntegerExceptions $exceptions)
    {
        $this->base = $base;
        $this->exceptions = $exceptions;
    }

    public function base(): int
    {
        return $this->base->base();
    }

    public function integer(string $numeral): int
    {
        return $this->numberAsInt(new StringNumeral($numeral), $numeral);
    }

    private function numberAsInt(StringNumeral $number, string $numeralString): int
    {
        try {
            return $number->asInt($this->base);
        } catch (NumeralFormatException $exception) {
            throw $this->exceptions->formatException($this->base, $numeralString);
        } catch (NumeralOverflowException $exception) {
            throw $this->exceptions->overflowException($this->base, $numeralString);
        }
    }
}
