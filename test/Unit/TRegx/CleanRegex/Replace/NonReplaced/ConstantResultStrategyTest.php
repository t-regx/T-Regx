<?php
namespace Test\Unit\TRegx\CleanRegex\Replace\NonReplaced;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantReturnStrategy;

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
        $this->assertEquals('constant', $result);
    }
}
