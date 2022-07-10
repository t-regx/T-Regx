<?php
namespace Test\Feature\CleanRegex\Match\stream\forEach_;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldNotInvokeForEach_forUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('Foo')->match('Bar');
        // when
        $stream->forEach(Functions::fail());
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldPassThrough_forEach()
    {
        // given
        $stream = Pattern::of('Foo')->match('Foo')->stream();
        // then
        $this->expectException(UnmatchedStreamException::class);
        // when
        $stream->forEach(Functions::throws(new UnmatchedStreamException()));
    }
}
