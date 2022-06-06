<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Word;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Word\AlterationWord;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Word\AlterationWord
 */
class AlterationWordTest extends TestCase
{
    /**
     * @test
     */
    public function shouldEscape()
    {
        // given
        $word = new AlterationWord(['/()', '^#$']);
        // when
        $result = $word->escaped('~');
        // then
        $this->assertSame('(?:/\(\)|\^\#\$)', $result);
    }

    /**
     * @test
     */
    public function shouldEscapeDelimiter()
    {
        // given
        $word = new AlterationWord(['a', '%b']);
        // when
        $result = $word->escaped('%');
        // then
        $this->assertSame('(?:a|\%b)', $result);
    }

    /**
     * @test
     */
    public function shouldRemoveDuplicates()
    {
        // given
        $word = new AlterationWord(['a', '', '', 'b', 'a']);
        // when
        $result = $word->escaped('/');
        // then
        $this->assertSame('(?:a||b)', $result);
    }

    /**
     * @test
     */
    public function shouldNotRemoveFalsyStrings()
    {
        // given
        $word = new AlterationWord(['|', ' ', '0']);
        // when
        $result = $word->escaped('/');
        // then
        $this->assertSame('(?:\||\ |0)', $result);
    }

    /**
     * @test
     */
    public function shouldThrowForArrayValues()
    {
        // given
        $word = new AlterationWord(['|', []]);
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid bound alternate value. Expected string, but array (0) given');
        // when
        $word->escaped('/');
    }

    /**
     * @test
     */
    public function shouldThrowForIntegerValues()
    {
        // given
        $word = new AlterationWord(['|', 5]);
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid bound alternate value. Expected string, but integer (5) given');
        // when
        $word->escaped('/');
    }

    /**
     * @test
     */
    public function shouldThrowForFalse()
    {
        // given
        $word = new AlterationWord([false]);
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid bound alternate value. Expected string, but boolean (false) given');
        // when
        $word->escaped('/');
    }

    /**
     * @test
     */
    public function shouldGetWithFalsyString()
    {
        // given
        $word = new AlterationWord(['0']);
        // when
        $escaped = $word->escaped('/');
        // then
        $this->assertSame('(?:0)', $escaped);
    }
}
