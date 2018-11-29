<?php
namespace Test\Integration\TRegx\CleanRegex\Split\inc;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\MissingSplitDelimiterGroupException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\SplitPattern;

class SplitPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplit_includingDelimiter()
    {
        // given
        $splitPattern = $this->split('([.])', '192..168...172..16');

        // when
        $result = $splitPattern->inc();

        // then
        $empty = '';
        $this->assertEquals(['192', '.', $empty, '.', '168', '.', $empty, '.', $empty, '.', '172', '.', $empty, '.', '16'], $result);
    }

    /**
     * @test
     */
    public function shouldReturn_unchanged()
    {
        // given
        $splitPattern = $this->split('(9)', 'Foo,Bar,Cat');

        // when
        $matches = $splitPattern->inc();

        // then
        $this->assertEquals(['Foo,Bar,Cat'], $matches);
    }

    /**
     * @test
     */
    public function shouldThrow_onMissingCapturingGroup_inc()
    {
        // given
        $splitPattern = $this->split(',', 'One,');

        // then
        $this->expectException(MissingSplitDelimiterGroupException::class);

        // when
        $splitPattern->inc();
    }

    private function split(string $pattern, string $subject): SplitPattern
    {
        return new SplitPattern(new Pattern($pattern), new SubjectableImpl($subject));
    }
}
