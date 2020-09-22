<?php
namespace Test\Unit\TRegx\CleanRegex\Replace\NonReplaced;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Replace\NonReplaced\ComputedSubjectStrategy;

class ComputedSubjectStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCompute()
    {
        // given
        $strategy = new ComputedSubjectStrategy(function (string $input) {
            return "*$input*";
        });

        // when
        $result = $strategy->substitute("test");

        // then
        $this->assertEquals('*test*', $result);
    }
}
