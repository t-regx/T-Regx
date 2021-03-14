<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\flatMap;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Internal;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $pattern = $this->getMatchPattern('Nice 1 matching 2 pattern');

        // when
        $map = $pattern->flatMap('str_split');

        // then
        $expected = [
            'N', 'i', 'c', 'e',
            'm', 'a', 't', 'c', 'h', 'i', 'n', 'g',
            'p', 'a', 't', 't', 'e', 'r', 'n'
        ];
        $this->assertSame($expected, $map);
    }

    /**
     * @test
     */
    public function shouldMap_withKeys()
    {
        // given
        $pattern = $this->getMatchPattern('Nice 1 matching 2 pattern');

        // when
        $map = $pattern->flatMap(function (Detail $detail) {
            return [$detail->text() => $detail->offset()];
        });

        // then
        $expected = [
            'Nice'     => 0,
            'matching' => 7,
            'pattern'  => 18
        ];
        $this->assertSame($expected, $map);
    }

    /**
     * @test
     */
    public function shouldMap_withDetails()
    {
        // given
        $pattern = $this->getMatchPattern("Nice matching pattern");
        $counter = 0;
        $matches = ['Nice', 'matching', 'pattern'];

        // when
        $pattern->flatMap(function (Detail $detail) use (&$counter, $matches) {
            // then
            $this->assertSame($matches[$counter], $detail->text());
            $this->assertSame($counter++, $detail->index());
            $this->assertSame("Nice matching pattern", $detail->subject());
            $this->assertSame($matches, $detail->all());

            return [];
        });
    }

    /**
     * @test
     */
    public function shouldNotInvokeMap_onNotMatchingSubject()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // when
        $pattern->flatMap(Functions::fail());

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // when
        $map = $pattern->flatMap(Functions::fail());

        // then
        $this->assertEmpty($map, 'Failed asserting that flatMap() returned an empty array');
    }

    /**
     * @test
     */
    public function shouldThrow_onNonArrayReturnType()
    {
        // given
        $pattern = $this->getMatchPattern('Nice 1 matching 2 pattern');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage("Invalid flatMap() callback return type. Expected array, but string ('string') given");

        // when
        $pattern->flatMap(Functions::constant('string'));
    }

    private function getMatchPattern($subject): MatchPattern
    {
        return new MatchPattern(Internal::pattern("([A-Z])?[a-z']+"), $subject);
    }
}
