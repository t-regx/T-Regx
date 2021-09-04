<?php
namespace Test\Utils\Impl;

use AssertionError;
use Throwable;
use TRegx\CleanRegex\Replace\Details\Modification;

class ThrowModification extends Modification
{
    public function __construct()
    {
        parent::__construct(new ThrowEntry(), '', -1);
    }

    public function subject(): string
    {
        throw $this->fail();
    }

    public function offset(): int
    {
        throw $this->fail();
    }

    public function byteOffset(): int
    {
        throw $this->fail();
    }

    private function fail(): Throwable
    {
        return new AssertionError("Failed to assert that Modification wasn't used");
    }
}
