<?php
namespace Test\Unit\CleanRegex\Internal\Match;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Model\ConstantEntry;
use Test\Fakes\CleanRegex\Internal\Model\OffsetEntry;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use TRegx\CleanRegex\Internal\Offset\SubjectCoordinate;
use TRegx\CleanRegex\Internal\Subject;

/**
 * @covers \TRegx\CleanRegex\Internal\Offset\SubjectCoordinate
 */
class SubjectCoordinateTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetCharactersOffset()
    {
        // given
        $coordinate = new SubjectCoordinate(new OffsetEntry(4), new Subject('foo bar'));
        // when
        $offset = $coordinate->characterOffset();
        // then
        $this->assertSame(4, $offset);
    }

    /**
     * @test
     * @depends shouldGetCharactersOffset
     */
    public function shouldGetCharactersOffsetUnicode()
    {
        // given
        $coordinate = new SubjectCoordinate(new OffsetEntry(4), new Subject('łść'));
        // when
        $offset = $coordinate->characterOffset();
        // then
        $this->assertSame(2, $offset);
    }

    /**
     * @test
     */
    public function shouldGetByteOffset()
    {
        // given
        $coordinate = new SubjectCoordinate(new OffsetEntry(4), new Subject('łść'));
        // when
        $offset = $coordinate->byteOffset();
        // then
        $this->assertSame(4, $offset);
    }

    /**
     * @test
     */
    public function shouldGetCharactersTail()
    {
        // given
        $coordinate = new SubjectCoordinate(new ConstantEntry('bar', 4), new Subject('foo bar'));
        // when
        $offset = $coordinate->characterTail();
        // then
        $this->assertSame(7, $offset);
    }

    /**
     * @test
     * @depends shouldGetCharactersTail
     */
    public function shouldGetCharactersTailUnicode()
    {
        // given
        $coordinate = new SubjectCoordinate(new ConstantEntry('ść', 2), new Subject('łść'));
        // when
        $tail = $coordinate->characterTail();
        // then
        $this->assertSame(3, $tail);
    }

    /**
     * @test
     */
    public function shouldGetByteTail()
    {
        // given
        $coordinate = new SubjectCoordinate(new ConstantEntry('ść', 2), new Subject('łść'));
        // when
        $tail = $coordinate->byteTail();
        // then
        $this->assertSame(6, $tail);
    }

    /**
     * @test
     */
    public function shouldGetLength()
    {
        // given
        $coordinate = new SubjectCoordinate(new ConstantEntry('śćł€', 2), new ThrowSubject());
        // when
        $length = $coordinate->characterLength();
        // then
        $this->assertSame(4, $length);
    }

    /**
     * @test
     */
    public function shouldGetByteLength()
    {
        // given
        $coordinate = new SubjectCoordinate(new ConstantEntry('śćł€', 2), new ThrowSubject());
        // when
        $length = $coordinate->byteLength();
        // then
        $this->assertSame(9, $length);
    }
}
