<?php
namespace Test\Feature\CleanRegex\Match\getIterator;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses, AssertsDetail;

    /**
     * @test
     */
    public function shouldGetIterator()
    {
        // given
        $match = Pattern::of('\w+')->match('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger');
        // when
        $iterator = $match->getIterator();
        // then
        $details = [];
        foreach ($iterator as $match) {
            $details[] = $match;
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
        $match = Pattern::of('\w+')->match('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger');
        // when
        $iterator = $match->getIterator();
        // then
        $indices = [];
        foreach ($iterator as $index => $match) {
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
        $match = Pattern::of('[A-Z]+')->match('Nice matching pattern');
        // when
        $iterator = $match->getIterator();
        // then
        $this->assertTrue($iterator->valid());
    }

    /**
     * @test
     */
    public function shouldIterator_not_hasNext_onUnmatchedSubject()
    {
        // given
        $match = Pattern::of('Foo')->match('Bar');
        // when
        $iterator = $match->getIterator();
        // then
        $this->assertFalse($iterator->valid());
    }
}
