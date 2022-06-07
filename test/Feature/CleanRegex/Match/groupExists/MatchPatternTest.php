<?php
namespace Test\Feature\CleanRegex\Match\groupExists;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
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
        $match = Pattern::of('(Foo)(Bar)?')->match('Foo');
        // when
        $this->assertTrue($match->groupExists(2));
        $this->assertFalse($match->groupExists(3));
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
        $match = Pattern::of('+')->match('Foo');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->groupExists(2);
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
        $match = Pattern::of('Foo')->match('Foo');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");
        // when
        $match->groupExists('2group');
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeIndex()
    {
        // given
        $match = Pattern::of('Foo')->match('Foo');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group index must be a non-negative integer, but -1 given");
        // when
        $match->groupExists(-1);
    }
}
