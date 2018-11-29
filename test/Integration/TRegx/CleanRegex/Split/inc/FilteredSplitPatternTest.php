<?php
namespace Test\Integration\TRegx\CleanRegex\Split\inc;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\MissingSplitDelimiterGroupException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Split\FilteredSplitPattern;

class FilteredSplitPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplit()
    {
        // given
        $splitPattern = $this->split('([.])', '192..168...172..16');

        // when
        $result = $splitPattern->inc();

        // then
        $this->assertEquals(['192', '.', '.', '168', '.', '.', '.', '172', '.', '.', '16'], $result);
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
    public function shouldSplit_namedGroup()
    {
        // given
        $splitPattern = $this->split('(,)', 'One,,Two,,Three');

        // when
        $matches = $splitPattern->inc();

        // then
        $this->assertEquals(['One', ',', ',', 'Two', ',', ',', 'Three'], $matches);
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

    private function split($pattern, $subject): FilteredSplitPattern
    {
        return new FilteredSplitPattern(new Pattern($pattern), new SubjectableImpl($subject));
    }
}
