<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use PHPUnit\Framework\TestCase;

class DefaultStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeDefault_returningNull()
    {
        // given
        $strategy = new DefaultStrategy();

        // when
        $result = $strategy->substitute('');

        // then
        $this->assertNull($result);
    }
}
