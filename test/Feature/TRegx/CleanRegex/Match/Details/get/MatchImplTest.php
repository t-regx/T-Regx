<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\get;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Match;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroup()
    {
        // given
        pattern('Hello (?<one>there)')->match('Hello there, General Kenobi')->first(function (Match $match) {
            // when
            $group = $match->get('one');

            // then
            $this->assertSame('there', $group);
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
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'one', but the group was not matched");

        // given
        pattern($pattern)->match($subject)->first(function (Match $match) {
            // when
            $match->get('one');
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
        $this->expectExceptionMessage("Nonexistent group: 'two'");

        // given
        pattern('(?<one>hello)')->match('hello')->first(function (Match $match) {
            // when
            $match->get('two');
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
