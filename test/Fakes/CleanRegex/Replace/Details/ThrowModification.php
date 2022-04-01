<?php
namespace Test\Fakes\CleanRegex\Replace\Details;

use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Replace\Details\Modification;

class ThrowModification implements Modification
{
    use Fails;

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
}
