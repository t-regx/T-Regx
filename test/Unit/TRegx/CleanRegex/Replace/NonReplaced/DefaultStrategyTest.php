<?php
namespace Test\Unit\TRegx\CleanRegex\Replace\NonReplaced;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;

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
