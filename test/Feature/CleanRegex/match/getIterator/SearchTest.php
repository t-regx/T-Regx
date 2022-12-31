<?php
namespace Test\Feature\CleanRegex\match\getIterator;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use TestCasePasses, AssertsDetail;

    /**
     * @test
     */
    public function shouldGetIterator()
    {
        // given
        $match = Pattern::of('\w+')->search('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger');
        // when
        $iterator = $match->getIterator();
        // then
        $texts = [];
        foreach ($iterator as $text) {
            $texts[] = $text;
        }
        $this->assertSame(['Father', 'Mother', 'Maiden', 'Crone', 'Warrior', 'Smith', 'Stranger'], $texts);
    }

    /**
     * @test
     */
    public function shouldGetIterator_sequentialKeys()
    {
        // given
        $match = Pattern::of('\w+')->search('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger');
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
        $match = Pattern::of('[A-Z]+')->search('Nice matching pattern');
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
        $match = Pattern::of('Foo')->search('Bar');
        // when
        $iterator = $match->getIterator();
        // then
        $this->assertFalse($iterator->valid());
    }
}
