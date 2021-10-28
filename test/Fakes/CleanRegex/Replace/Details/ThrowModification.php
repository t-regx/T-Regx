<?php
namespace Test\Fakes\CleanRegex\Replace\Details;

use Test\Fakes\CleanRegex\Internal\Model\Match\ThrowEntry;
use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Replace\Details\Modification;

class ThrowModification extends Modification
{
    use Fails;

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
}
