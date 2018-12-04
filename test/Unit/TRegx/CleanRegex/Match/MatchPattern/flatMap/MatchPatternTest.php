<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\flatMap;

use TRegx\CleanRegex\Exception\CleanRegex\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\MatchPattern;
use PHPUnit\Framework\TestCase;

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
        $this->assertEquals($expected, $map);
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
        $pattern->flatMap(function (Match $match) use (&$counter, $matches) {

            // then
            $this->assertEquals($matches[$counter], $match->text());
            $this->assertEquals($counter++, $match->index());
            $this->assertEquals("Nice matching pattern", $match->subject());
            $this->assertEquals($matches, $match->all());

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
        $pattern->flatMap(function () {

            // then
            $this->assertTrue(false, "Failed asserting that flatMap() is not invoked for not matching subject");
        });

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
        $map = $pattern->flatMap(function () {
        });

        // then
        $this->assertEquals([], $map, 'Failed asserting that flatMap() returned an empty array');
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
        $this->expectExceptionMessage("Invalid flatMap() callback return type. Expected array, but string ('word') given");

        // when
        $pattern->flatMap(function () {
            return 'word';
        });
    }

    private function getMatchPattern($subject): MatchPattern
    {
        return new MatchPattern(new Pattern("([A-Z])?[a-z']+"), $subject);
    }
}
