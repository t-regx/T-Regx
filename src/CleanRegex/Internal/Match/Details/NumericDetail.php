<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Internal\Match\Numeral\MatchBase;
use TRegx\CleanRegex\Internal\Match\Numeral\MatchExceptions;
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
        return (new MatchBase($base, new MatchExceptions()))->integer($this->entry->text());
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
