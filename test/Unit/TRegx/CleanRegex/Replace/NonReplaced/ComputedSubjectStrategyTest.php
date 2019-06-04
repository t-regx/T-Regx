<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use PHPUnit\Framework\TestCase;

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
