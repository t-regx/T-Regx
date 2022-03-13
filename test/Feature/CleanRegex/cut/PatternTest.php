<?php
namespace Test\Feature\TRegx\CleanRegex\cut;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\UnevenCutException;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCut()
    {
        // when
        [$first, $second] = Pattern::literal('|')->cut('foo|bar');
        // then
        $this->assertSame('foo', $first);
        $this->assertSame('bar', $second);
    }

    /**
     * @test
     */
    public function shouldCutEmptyBeforeAndAfter()
    {
        // when
        [$first, $second] = pattern('%')->cut('%');
        // then
        $this->assertSame('', $first);
        $this->assertSame('', $second);
    }

    /**
     * @test
     */
    public function shouldNotIncludeSeparator()
    {
        // when
        [$first, $second] = pattern('(%)')->cut('one%two');
        // then
        $this->assertSame('one', $first);
        $this->assertSame('two', $second);
    }

    /**
     * @test
     */
    public function shouldThrowForThreeCuts()
    {
        // given
        $pattern = pattern('%');
        // then
        $this->expectException(UnevenCutException::class);
        $this->expectExceptionMessage("Expected the pattern to make exactly 1 cut, but 2 or more cuts were matched");
        // when
        $pattern->cut('one%two%three%four');
    }

    /**
     * @test
     */
    public function shouldThrowForTwoCuts()
    {
        // given
        $pattern = pattern('%');
        // then
        $this->expectException(UnevenCutException::class);
        $this->expectExceptionMessage("Expected the pattern to make exactly 1 cut, but 2 or more cuts were matched");
        // when
        $pattern->cut('one%two%three');
    }

    /**
     * @test
     */
    public function shouldThrowForZeroCuts()
    {
        // given
        $pattern = pattern('%');
        // then
        $this->expectException(UnevenCutException::class);
        $this->expectExceptionMessage("Expected the pattern to make exactly 1 cut, but the pattern doesn't match the subject");
        // when
        $pattern->cut('one');
    }

    /**
     * @test
     */
    public function shouldCutWithZeroLengthMatch()
    {
        // given
        $pattern = pattern('(?=bar)');
        // when
        $pieces = $pattern->cut('bar');
        // then
        $this->assertSame(['', 'bar'], $pieces);
    }
}
