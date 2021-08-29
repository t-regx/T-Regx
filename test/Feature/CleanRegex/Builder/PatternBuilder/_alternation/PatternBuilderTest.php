<?php
namespace Test\Feature\TRegx\CleanRegex\Builder\PatternBuilder\_alternation;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $alteration = ['Hello #5', 'Yes?:)'];
        $pattern = Pattern::inject('You/her, (are|is) @ (you|her)', [$alteration]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('#You/her, (are|is) (?:Hello\ \#5|Yes\?\:\)) (you|her)#', $pattern);
    }
}
