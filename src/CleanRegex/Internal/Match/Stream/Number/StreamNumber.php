<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Number;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Numeral\NumeralFormatException;
use TRegx\CleanRegex\Internal\Numeral\NumeralOverflowException;
use TRegx\CleanRegex\Internal\Numeral\StringNumeral;

class StreamNumber implements Number
{
    /** @var StringNumeral */
    private $number;
    /** @var Base */
    private $base;
    /** @var string */
    private $string;

    public function __construct(string $string, Base $base)
    {
        $this->number = new StringNumeral($string);
        $this->base = $base;
        $this->string = $string;
    }

    public function toInt(): int
    {
        try {
            return $this->number->asInt($this->base);
        } catch (NumeralOverflowException $exception) {
            throw IntegerOverflowException::forStream($this->string, $this->base);
        } catch (NumeralFormatException $exception) {
            throw IntegerFormatException::forStream($this->string, $this->base);
        }
    }
}
