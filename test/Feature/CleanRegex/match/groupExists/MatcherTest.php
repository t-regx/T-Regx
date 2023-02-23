<?php
namespace Test\Feature\CleanRegex\match\groupExists;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use TestCasePasses, CausesBacktracking;

    /**
     * @test
     */
    public function shouldPatternHaveGroup0()
    {
        $this->assertTrue(Pattern::of('Foo')->match('Foo')->groupExists(0));
        $this->assertTrue(Pattern::of('Foo')->match('Bar')->groupExists(0));
    }

    /**
     * @test
     */
    public function shouldPatternHaveGroupUnmatched_last()
    {
        // given
        $matcher = Pattern::of('(Foo)(Bar)?')->match('Foo');
        // when
        $this->assertTrue($matcher->groupExists(2));
        $this->assertFalse($matcher->groupExists(3));
    }

    /**
     * @test
     */
    public function shouldPatternNotHaveGroup()
    {
        $this->assertFalse(Pattern::of('Foo')->match('Foo')->groupExists(1));
        $this->assertFalse(Pattern::of('Foo')->match('Bar')->groupExists(1));
    }

    /**
     * @test
     */
    public function shouldPatternHaveGroup1()
    {
        $this->assertTrue(Pattern::of('(Foo)')->match('Foo')->groupExists(1));
        $this->assertTrue(Pattern::of('(Foo)')->match('Bar')->groupExists(1));
    }

    /**
     * @test
     */
    public function shouldPatternNotHaveNonCapturingGroup()
    {
        $this->assertFalse(Pattern::of('(?:Foo)')->match('Foo')->groupExists(1));
        $this->assertFalse(Pattern::of('(?:Foo)')->match('Bar')->groupExists(1));
    }

    /**
     * @test
     */
    public function shouldPatternHaveNamedGroup()
    {
        $this->assertTrue(Pattern::of('-(?<name>Foo)')->match('Foo')->groupExists('name'));
        $this->assertTrue(Pattern::of('-(?<name>Foo)')->match('Bar')->groupExists('name'));
    }

    /**
     * @test
     */
    public function shouldPatternNotHaveNamedGroup()
    {
        $this->assertFalse(Pattern::of('-(?<name>Foo)')->match('Foo')->groupExists('missing'));
        $this->assertFalse(Pattern::of('-(?<name>Foo)')->match('Bar')->groupExists('missing'));
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $matcher = Pattern::of('+')->match('Foo');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $matcher->groupExists(2);
    }

    /**
     * @test
     */
    public function shouldHasGroupNotCauseCatastrophicBacktracking()
    {
        // when
        $this->backtrackingMatch()->groupExists(2);
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedGroupName()
    {
        // given
        $matcher = Pattern::of('Foo')->match('Foo');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");
        // when
        $matcher->groupExists('2group');
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeIndex()
    {
        // given
        $matcher = Pattern::of('Foo')->match('Foo');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group index must be a non-negative integer, but -1 given");
        // when
        $matcher->groupExists(-1);
    }

    /**
     * @test
     * @dataProvider groups
     * @param string $pattern
     */
    public function shouldPcreNotationGroupExist(string $pattern)
    {
        // given
        $matcher = Pattern::of($pattern)->match('value');
        // when, then
        $this->assertTrue($matcher->groupExists('group'));
    }

    public function groups(): array
    {
        return [
            ['ab (?<group>c)'],
            ['ab (?P<group>c)'],
            ["ab (?'group'c)"],
            ["ab (?'group'c (?<P>xd))"],
        ];
    }

    /**
     * @test
     * @dataProvider nonGroups
     * @param string $pattern
     */
    public function shouldPcreNotationGroupNotExist(string $pattern)
    {
        // given
        $matcher = Pattern::of($pattern)->match('value');
        // when, then
        $this->assertFalse($matcher->groupExists('group'));
    }

    public function nonGroups(): array
    {
        return [
            ['ab \(?<group>c\)'],
            ['ab \(?P<group>c\)'],
            ["ab \(?'group'c\)"],
        ];
    }
}
