<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Flags;

/**
 * @covers \TRegx\CleanRegex\Internal\Flags
 */
class FlagsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCastToStringWithoutDuplicates()
    {
        // given
        $flags = new Flags('hello');

        // then
        $this->assertSame('helo', "$flags");
    }
}
