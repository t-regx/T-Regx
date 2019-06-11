<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Match;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroup()
    {
        // given
        pattern('Hello (?<one>there)')
            ->match('Hello there, General Kenobi')
            ->first(function (Match $match) {

                // then
                $this->assertEquals('there', $match->group('one'));
                $this->assertEquals('there', $match->group('one')->text());
                $this->assertEquals(6, $match->group('one')->offset());
                $this->assertTrue($match->group('one')->matched());

                $this->assertTrue($match->hasGroup('one'));
                $this->assertFalse($match->hasGroup('two'));
            });
    }

    /**
     * @test
     */
    public function shouldGet_groupTextLength()
    {
        // given
        pattern('(\p{L}+)', 'u')
            ->match('Łomża')
            ->first(function (Match $match) {
                // then
                $this->assertEquals('Łomża', $match->group(1)->text());
                $this->assertEquals(5, $match->group(1)->textLength());
            });
    }

    /**
     * @test
     */
    public function shouldGetGroup_all_matched()
    {
        // given
        pattern('Hello (?<one>there|here)?')
            ->match('Hello there, General Kenobi, maybe Hello and Hello here')
            ->first(function (Match $match) {

                // when
                $all = $match->all();
                $groupAll = $match->group('one')->all();

                // then
                $this->assertEquals(['Hello there', 'Hello ', 'Hello here'], $all);
                $this->assertEquals(['there', null, 'here'], $groupAll);
            });
    }

    /**
     * @test
     */
    public function shouldGetGroup_all_unmatched()
    {
        // given
        pattern('Hello (?<one>there|here)?')
            ->match('Hello , General Kenobi, maybe Hello there and Hello here')
            ->first(function (Match $match) {

                // when
                $groupAll = $match->group('one')->all();

                // then
                $this->assertEquals([null, 'there', 'here'], $groupAll);
            });
    }

    /**
     * @test
     */
    public function shouldGetGroup_empty_string()
    {
        // when
        pattern('Hello (?<one>there|here|)')
            ->match('Hello there, General Kenobi, maybe Hello and Hello here')
            ->first(function (Match $match) {

                // when
                $all = $match->all();
                $groupAll = $match->group('one')->all();

                // then
                $this->assertEquals(['Hello there', 'Hello ', 'Hello here'], $all);
                $this->assertEquals(['there', '', 'here'], $groupAll);
            });
    }

    /**
     * @test
     * @dataProvider shouldGroup_notMatch_dataProvider
     * @param string $pattern
     * @param string $subject
     */
    public function shouldGroup_notMatch(string $pattern, string $subject)
    {
        // given
        pattern($pattern)->match($subject)->first(function (Match $match) {

            $group = $match->group('one');

            // when
            $matches = $group->matched();

            // then
            $this->assertFalse($matches);
        });
    }

    public function shouldGroup_notMatch_dataProvider()
    {
        return [
            ['Hello (?<one>there)?', 'Hello XX, General Kenobi'],
            ['Hello (?<one>there)?(?<two>XX)', 'Hello XX, General Kenobi'],
        ];
    }

    /**
     * @test
     */
    public function shouldThrow_onMissingGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);

        // when
        pattern('(?<one>hello)')
            ->match('hello')
            ->first(function (Match $match) {
                $match->group('two');
            });
    }

    /**
     * @test
     */
    public function shouldValidateGroupName()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index can only be an integer or a string, given: boolean (true)');

        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Match $match) {
                // when
                $match->group(true);
            });
    }
}
