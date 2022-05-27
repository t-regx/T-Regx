<?php
namespace Test\Fakes\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Model\Entry;

class ConstantEntry implements Entry
{
    /** @var string */
    private $text;
    /** @var int */
    private $offset;

    public function __construct(string $text, int $offset)
    {
        $this->text = $text;
        $this->offset = $offset;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function byteOffset(): int
    {
        return $this->offset;
    }
}
