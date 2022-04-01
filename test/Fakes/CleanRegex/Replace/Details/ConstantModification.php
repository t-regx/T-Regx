<?php
namespace Test\Fakes\CleanRegex\Replace\Details;

use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Replace\Details\Modification;

class ConstantModification implements Modification
{
    use Fails;

    /** @var int */
    private $offset;
    /** @var int */
    private $byteOffset;

    public function __construct(int $offset, int $byteOffset)
    {
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
}
