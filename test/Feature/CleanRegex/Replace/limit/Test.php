<?php
namespace Test\Feature\CleanRegex\Replace\limit;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    use TestCasePasses, CausesBacktracking;

    /**
     * @test
     */
    public function shouldThrowForNegativeLimitOne()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -1');
        // when
        pattern('Foo')->replace('Bar')->limit(-1);
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeLimitFour()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -4');
        // when
        pattern('Foo')->replace('Bar')->limit(-4);
    }

    /**
     * @test
     */
    public function shouldReplace()
    {
        // when
        $replaced = pattern('\d+')->replace('127.0.0.1')->limit(3)->with('X');
        // then
        $this->assertSame('X.X.X.1', $replaced);
    }

    /**
     * @test
     * @dataProvider limitAndExpectedResults
     * @param int $limit
     * @param string $expectedResult
     */
    public function shouldReplaceNOccurrences(int $limit, string $expectedResult)
    {
        // when
        $replaced = pattern('[0-3]')->replace('0, 1, 2, 3')->limit($limit)->with('*');
        // then
        $this->assertSame($expectedResult, $replaced);
    }

    function limitAndExpectedResults(): array
    {
        return [
            [0, '0, 1, 2, 3'],
            [1, '*, 1, 2, 3'],
            [2, '*, *, 2, 3'],
            [3, '*, *, *, 3'],
        ];
    }

    /**
     * @test
     */
    public function shouldReplace_withReferences()
    {
        // when
        $replaced = pattern('(\d+)')->replace('127.0.0.1')->limit(2)->withReferences('<$1>');
        // then
        $this->assertSame('<127>.<0>.0.1', $replaced);
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        pattern('?')->replace('Foo')->limit(0)->withReferences('Bar');
    }

    /**
     * @test
     */
    public function shouldReplace_callback()
    {
        // when
        $replaced = pattern('\d+')->replace('127.230.35.10')->limit(2)->callback(Functions::charAt(0));
        // then
        $this->assertSame('1.2.35.10', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_withGroup()
    {
        // when
        $replaced = pattern('!(\d+)!')->replace('!123!, !345!, !678!')->limit(2)->withGroup(1);
        // then
        $this->assertSame('123, 345, !678!', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceLimit2_CatastrophicBacktrackingAt3()
    {
        // when
        $this->backtrackingReplace(2)->limit(2)->callback(Functions::constant('Foo'));
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldReplaceLimit3_CatastrophicBacktrackingAt3()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        $this->backtrackingReplace(2)->limit(3)->callback(Functions::constant('Foo'));
    }
}
