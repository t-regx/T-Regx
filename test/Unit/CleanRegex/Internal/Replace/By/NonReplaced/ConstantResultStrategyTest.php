<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ConstantReturnStrategy;

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
        $result = $strategy->substitute('');

        // then
        $this->assertSame('constant', $result);
    }
}
