<?php
namespace Test\Feature\CleanRegex\match\getIterator;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use TestCasePasses, AssertsDetail;

    /**
     * @test
     */
    public function shouldGetIterator()
    {
        // given
        $matcher = Pattern::of('\w+')->match('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger');
        // when
        $iterator = $matcher->getIterator();
        // then
        $details = [];
        foreach ($iterator as $matcher) {
            $details[] = $matcher;
        }
        $this->assertDetailsAll(['Father', 'Mother', 'Maiden', 'Crone', 'Warrior', 'Smith', 'Stranger'], ...$details);
        $this->assertDetailsIndexed(...$details);
    }

    /**
     * @test
     */
    public function shouldGetIterator_sequentialKeys()
    {
        // given
        $matcher = Pattern::of('\w+')->match('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger');
        // when
        $iterator = $matcher->getIterator();
        // then
        $indices = [];
        foreach ($iterator as $index => $matcher) {
            $indices[] = $index;
        }
        $this->assertSame([0, 1, 2, 3, 4, 5, 6], $indices);
    }

    /**
     * @test
     */
    public function shouldIterator_hasNext()
    {
        // given
        $matcher = Pattern::of('[A-Z]+')->match('Nice matching pattern');
        // when
        $iterator = $matcher->getIterator();
        // then
        $this->assertTrue($iterator->valid());
    }

    /**
     * @test
     */
    public function shouldIterator_not_hasNext_onUnmatchedSubject()
    {
        // given
        $matcher = Pattern::of('Foo')->match('Bar');
        // when
        $iterator = $matcher->getIterator();
        // then
        $this->assertFalse($iterator->valid());
    }
}
