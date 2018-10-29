<?php
namespace Test\Unit\TRegx\CleanRegex\Split;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\SplitPattern;

class SplitPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplit_excludingDelimiter()
    {
        // given
        $splitPattern = new SplitPattern(new Pattern('([.+|])'), new SubjectableImpl('192..168+++172||16'));

        // when
        $result = $splitPattern->ex();

        // then
        $this->assertEquals(['192', '', '168', '', '', '172', '', '16'], $result);
    }

    /**
     * @test
     */
    public function shouldSplit_includingDelimiter()
    {
        // given
        $splitPattern = new SplitPattern(new Pattern('([.+|])'), new SubjectableImpl('192..168+++172||16'));

        // when
        $result = $splitPattern->inc();

        // then
        $this->assertEquals(['192', '.', '', '.', '168', '+', '', '+', '', '+', '172', '|', '', '|', '16'], $result);
    }
}
