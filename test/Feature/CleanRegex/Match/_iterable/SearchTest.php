<?php
namespace Test\Feature\CleanRegex\Match\_iterable;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Match\Search;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class SearchTest extends TestCase
{
    use TestCasePasses, AssertsDetail;

    private function search(): Search
    {
        return pattern('\d+')->search('127.0.1.2');
    }

    /**
     * @test
     */
    public function shouldIterateSearch()
    {
        // given
        $texts = [];
        // when
        foreach ($this->search() as $text) {
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
        $texts = \iterator_to_array($this->search());
        // then
        $this->assertSame(['127', '0', '1', '2'], $texts);
    }

    /**
     * @test
     */
    public function shouldNotIterate_onUnmatchedSubject()
    {
        // given
        $search = Pattern::of('Foo')->search('Bar');
        // when
        foreach ($search as $detail) {
            $this->fail();
        }
        // then
        $this->pass();
    }
}
