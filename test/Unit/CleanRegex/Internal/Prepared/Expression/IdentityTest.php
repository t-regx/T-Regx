<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Expression;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Expression\Identity;

/**
 * @covers \TRegx\CleanRegex\Internal\Expression\Identity
 */
class IdentityTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $input = new Definition('/foo/', 'foo');
        $identity = new Identity($input);

        // when
        $actual = $identity->definition();

        // then
        $this->assertSame($input, $actual);
    }
}
