<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Model\Match\Entry;

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
