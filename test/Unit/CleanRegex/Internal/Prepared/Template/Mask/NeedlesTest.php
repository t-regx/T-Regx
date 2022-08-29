<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Template\Mask;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\Needles;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Template\Mask\Needles
 */
class NeedlesTest extends TestCase
{
    /**
     * @test
     */
    public function performanceShouldNotSplitOnEmptyTokens(): void
    {
        // given
        $needles = new Needles([]);
        // when
        $result = $needles->split('Welcome');
        // then
        $this->assertSame(['Welcome'], $result);
    }
}
