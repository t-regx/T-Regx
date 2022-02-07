<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Numeral\NumeralFormatException;
use TRegx\CleanRegex\Internal\Numeral\NumeralOverflowException;
use TRegx\CleanRegex\Internal\Numeral\StringNumeral;

class NumericDetail
{
    /** @var Entry */
    private $entry;

    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    public function asInteger(Base $base): int
    {
        return $this->textAsInteger($base, $this->entry->text());
    }

    private function textAsInteger(Base $base, string $text): int
    {
        $number = new StringNumeral($text);
        try {
            return $number->asInt($base);
        } catch (NumeralFormatException $exception) {
            throw IntegerFormatException::forMatch($text, $base);
        } catch (NumeralOverflowException $exception) {
            throw IntegerOverflowException::forMatch($text, $base);
        }
    }

    public function isInteger(Base $base): bool
    {
        $numeral = new StringNumeral($this->entry->text());
        try {
            $numeral->asInt($base);
            return true;
        } catch (NumeralFormatException | NumeralOverflowException $exception) {
            return false;
        }
    }
}
