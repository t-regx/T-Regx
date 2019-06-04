<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use PHPUnit\Framework\TestCase;

class ConstantResultStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnConstant()
    {
        // given
        $strategy = new ConstantResultStrategy('constant');

        // when
        $result = $strategy->substitute('');

        // then
        $this->assertEquals('constant', $result);
    }
}
