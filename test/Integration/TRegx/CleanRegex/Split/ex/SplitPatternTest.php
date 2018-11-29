<?php
namespace Test\Integration\TRegx\CleanRegex\Split\ex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\SplitPattern;

class SplitPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplit()
    {
        // given
        $splitPattern = $this->split('([.+|])', '192..168+++172||16');

        // when
        $result = $splitPattern->ex();

        // then
        $empty = '';
        $this->assertEquals(['192', $empty, '168', $empty, $empty, '172', $empty, '16'], $result);
    }

    /**
     * @test
     */
    public function shouldReturn_unchanged()
    {
        // given
        $splitPattern = $this->split('9', 'Foo,Bar,Cat');

        // when
        $matches = $splitPattern->ex();

        // then
        $this->assertEquals(['Foo,Bar,Cat'], $matches);
    }

    private function split($pattern, $subject): SplitPattern
    {
        return new SplitPattern(new Pattern($pattern), new SubjectableImpl($subject));
    }
}
