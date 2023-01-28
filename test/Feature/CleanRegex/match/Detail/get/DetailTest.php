<?php
namespace Test\Feature\CleanRegex\match\Detail\get;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
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
     * @dataProvider patternsWithGroups
     * @param Pattern $pattern
     */
    public function shouldGroup_forUnmatchedGroup_byName(Pattern $pattern)
    {
        // given
        $detail = $pattern->match('One,Two')->first();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'one', but the group was not matched");
        // when
        $detail->get('one');
    }

    public function patternsWithGroups(): array
    {
        return [
            [Pattern::of('(?<one>One){0}')],
            [Pattern::of('(?<one>One){0},(?<two>Two)')],
        ];
    }

    /**
     * @test
     */
    public function shouldGroup_forUnmatchedGroup_byIndex()
    {
        // given
        $detail = pattern('(Foo)(Bar){0}')->match('Foo')->first();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #2, but the group was not matched");
        // when
        $detail->get(2);
    }

    /**
     * @test
     */
    public function shouldThrow_forNonexistentGroup()
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
    public function shouldThrow_forInvalidGroup()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be an integer or a string, but boolean (true) given');
        // given
        $detail = pattern('(?<one>first) and (?<two>second)')->match('first and second')->first();
        // when
        $detail->group(true);
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::validGroups()
     * @param string|int $name
     */
    public function shouldGetGroup_validName(string $name)
    {
        // given
        $pattern = Pattern("(?<$name>Bar){0}", 'u');
        $detail = $pattern->match('Foo')->first();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group '$name', but the group was not matched");
        // when
        $detail->get($name);
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidGroupNames()
     * @param string|int $name
     */
    public function shouldThrowForMalformedName(string $name, string $expected)
    {
        // given
        $detail = Pattern::of('Foo', 'u')->match('Foo')->first();
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '$expected' given");
        // when
        $detail->get($name);
    }
}
