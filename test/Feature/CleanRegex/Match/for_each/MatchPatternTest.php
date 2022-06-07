<?php
namespace Test\Feature\CleanRegex\Match\for_each;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldGetMatch_withDetails()
    {
        // given
        $pattern = Pattern::of("([A-Z])?[a-z']+")->match("Nice matching pattern");
        $counter = 0;
        $matches = ['Nice', 'matching', 'pattern'];
        // when
        $pattern->forEach(function (Detail $detail) use (&$counter, $matches) {
            // then
            $this->assertSame($matches[$counter], $detail->text());
            $this->assertSame($counter++, $detail->index());
            $this->assertSame('Nice matching pattern', $detail->subject());
            $this->assertSame(['Nice', 'matching', 'pattern'], $detail->all());
        });
    }

    /**
     * @test
     */
    public function shouldNotInvokeCallback_onNotMatchingSubject()
    {
        // given
        $pattern = Pattern::of("([A-Z])?[a-z']+")->match('NOT MATCHING');
        // when
        $pattern->forEach(Functions::fail());
        // then
        $this->pass();
    }
}
