<?php
namespace Test\Feature\CleanRegex\Match\_iterable;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\Structure\Expect;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses, AssertsDetail, AssertsStructure;

    private function match(): MatchPattern
    {
        return pattern('\d+')->match('127.0.1.2');
    }

    /**
     * @test
     */
    public function shouldIterateMatch()
    {
        // given
        $details = [];
        // when
        foreach ($this->match() as $detail) {
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
        $details = \iterator_to_array($this->match());
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
    public function shouldNotIterate_onUnmatchedSubject()
    {
        // given
        $match = Pattern::of('Foo')->match('Bar');
        // when
        foreach ($match as $detail) {
            $this->fail();
        }
        // then
        $this->pass();
    }
}
