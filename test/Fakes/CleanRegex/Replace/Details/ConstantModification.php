<?php
namespace Test\Fakes\CleanRegex\Replace\Details;

use Test\Fakes\CleanRegex\Internal\Model\Match\ThrowEntry;
use Test\Utils\Fails;
use TRegx\CleanRegex\Replace\Details\Modification;

class ConstantModification extends Modification
{
    use Fails;

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
}
