<?php
namespace Test\Feature\CleanRegex\Match\Details\get;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;

class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroup()
    {
        // given
        $detail = pattern('Hello (?<one>there)')->match('Hello there, General Kenobi')->first();
        // when
        $group = $detail->get('one');
        // then
        $this->assertSame('there', $group);
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
        $detail = pattern($pattern)->match($subject)->first();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'one', but the group was not matched");
        // when
        $detail->get('one');
    }

    public function shouldGroup_notMatch_dataProvider(): array
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
        // given
        $detail = pattern('(?<one>hello)')->match('hello')->first();
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'two'");
        // when
        $detail->get('two');
    }

    /**
     * @test
     */
    public function shouldValidateGroupName()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be an integer or a string, but boolean (true) given');
        // given
        $detail = pattern('(?<one>first) and (?<two>second)')->match('first and second')->first();
        // when
        $detail->group(true);
    }
}
