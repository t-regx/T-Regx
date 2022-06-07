<?php
namespace Test\Feature\CleanRegex\Match\_iterable;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\Structure\Expect;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Match\Stream;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class MatchStreamPatternTest extends TestCase
{
    use TestCasePasses, AssertsDetail, AssertsStructure;

    private function stream(): Stream
    {
        return pattern('\d+')->match('127.0.1.2')->stream();
    }

    /**
     * @test
     */
    public function shouldIterateMatch()
    {
        // given
        $details = [];
        // when
        foreach ($this->stream() as $detail) {
            $details[] = $detail;
        }
        // then
        $this->assertStructure($details, [
            Expect::text('127'),
            Expect::text('0'),
            Expect::text('1'),
            Expect::text('2'),
        ]);
    }

    /**
     * @test
     */
    public function shouldGetMatchAsIterator()
    {
        // when
        $details = \iterator_to_array($this->stream());
        // then
        $this->assertStructure($details, [
            Expect::text('127'),
            Expect::text('0'),
            Expect::text('1'),
            Expect::text('2'),
        ]);
    }

    /**
     * @test
     */
    public function shouldGetMatchAsIterator_asInt()
    {
        // when
        $integers = \iterator_to_array($this->stream()->asInt());
        // then
        $this->assertSame([127, 0, 1, 2], $integers);
    }

    /**
     * @test
     */
    public function shouldNotIterate_onUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('Foo')->search('Bar')->stream();
        // when
        foreach ($stream as $detail) {
            $this->fail();
        }
        // then
        $this->pass();
    }
}
