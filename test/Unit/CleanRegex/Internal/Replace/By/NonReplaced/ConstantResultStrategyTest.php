<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\ThrowSubject;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ConstantReturnStrategy;

/**
 * @covers \TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ConstantReturnStrategy
 */
class ConstantResultStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnConstant()
    {
        // given
        $strategy = new ConstantReturnStrategy('constant');

        // when
        $result = $strategy->substitute(new ThrowSubject());

        // then
        $this->assertSame('constant', $result);
    }
}
