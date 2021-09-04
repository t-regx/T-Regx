<?php
namespace Test\Utils\Impl;

use AssertionError;
use Throwable;
use TRegx\CleanRegex\Replace\Details\Modification;

class ConstantModification extends Modification
{
    /** @var int */
    private $offset;
    /** @var int */
    private $byteOffset;

    public function __construct(int $offset, int $byteOffset)
    {
        parent::__construct(new ThrowEntry(), '', -1);
        $this->offset = $offset;
        $this->byteOffset = $byteOffset;
    }

    public function subject(): string
    {
        throw $this->fail();
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public function byteOffset(): int
    {
        return $this->byteOffset;
    }

    private function fail(): Throwable
    {
        return new AssertionError("Failed to assert that Modification wasn't used");
    }
}
