<?php
namespace Test\Unit\CleanRegex\Internal\Offset;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Offset\ByteOffset;

class ByteOffsetTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrowForNegativeOffset()
    {
        // then
        $this->expectException(InternalCleanRegexException::class);

        // when
        new ByteOffset(-1);
    }
}
