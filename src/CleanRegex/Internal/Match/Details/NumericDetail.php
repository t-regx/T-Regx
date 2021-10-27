<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Number\Base;
use TRegx\CleanRegex\Internal\Number\NumberFormatException;
use TRegx\CleanRegex\Internal\Number\NumberOverflowException;
use TRegx\CleanRegex\Internal\Number\StringNumber;

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
        $number = new StringNumber($text);
        try {
            return $number->asInt($base);
        } catch (NumberFormatException $exception) {
            throw IntegerFormatException::forMatch($text, $base);
        } catch (NumberOverflowException $exception) {
            throw IntegerOverflowException::forMatch($text, $base);
        }
    }

    public function isInteger(Base $base): bool
    {
        $stringNumber = new StringNumber($this->entry->text());
        try {
            $stringNumber->asInt($base);
            return true;
        } catch (NumberFormatException | NumberOverflowException $exception) {
            return false;
        }
    }
}
