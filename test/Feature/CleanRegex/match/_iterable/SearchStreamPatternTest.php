<?php
namespace Test\Feature\CleanRegex\match\_iterable;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Match\Stream;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class SearchStreamPatternTest extends TestCase
{
    use TestCasePasses, AssertsDetail;

    private function stream(): Stream
    {
        return pattern('\d+')->search('127.0.1.2')->stream();
    }

    /**
     * @test
     */
    public function shouldIterateSearch()
    {
        // given
        $texts = [];
        // when
        foreach ($this->stream() as $text) {
            $texts[] = $text;
        }
        // then
        $this->assertSame(['127', '0', '1', '2'], $texts);
    }

    /**
     * @test
     */
    public function shouldGetSearchAsIterator()
    {
        // when
        $texts = \iterator_to_array($this->stream());
        // then
        $this->assertSame(['127', '0', '1', '2'], $texts);
    }

    /**
     * @test
     */
    public function shouldGetSearchAsIterator_asInt()
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
