<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Model\Match\ConstantEntry;
use Test\Fakes\CleanRegex\Internal\Model\Match\OffsetEntry;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use TRegx\CleanRegex\Internal\Offset\SubjectCoordinates;
use TRegx\CleanRegex\Internal\StringSubject;

/**
 * @covers \TRegx\CleanRegex\Internal\Offset\SubjectCoordinates
 */
class SubjectCoordinatesTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetCharactersOffset()
    {
        // given
        $coordinates = new SubjectCoordinates(new OffsetEntry(4), new StringSubject('foo bar'));

        // when
        $offset = $coordinates->characterOffset();

        // then
        $this->assertSame(4, $offset);
    }

    /**
     * @test
     */
    public function shouldGetCharactersOffsetUnicode()
    {
        // given
        $coordinates = new SubjectCoordinates(new OffsetEntry(4), new StringSubject('łść'));

        // when
        $offset = $coordinates->characterOffset();

        // then
        $this->assertSame(2, $offset);
    }

    /**
     * @test
     */
    public function shouldGetByteOffset()
    {
        // given
        $coordinates = new SubjectCoordinates(new OffsetEntry(4), new StringSubject('łść'));

        // when
        $offset = $coordinates->byteOffset();

        // then
        $this->assertSame(4, $offset);
    }

    /**
     * @test
     */
    public function shouldGetCharactersTail()
    {
        // given
        $coordinates = new SubjectCoordinates(new ConstantEntry('bar', 4), new StringSubject('foo bar'));

        // when
        $offset = $coordinates->characterTail();

        // then
        $this->assertSame(7, $offset);
    }

    /**
     * @test
     */
    public function shouldGetCharactersTailUnicode()
    {
        // given
        $coordinates = new SubjectCoordinates(new ConstantEntry('ść', 2), new StringSubject('łść'));

        // when
        $tail = $coordinates->characterTail();

        // then
        $this->assertSame(3, $tail);
    }

    /**
     * @test
     */
    public function shouldGetByteTail()
    {
        // given
        $coordinates = new SubjectCoordinates(new ConstantEntry('ść', 2), new StringSubject('łść'));

        // when
        $tail = $coordinates->byteTail();

        // then
        $this->assertSame(6, $tail);
    }

    /**
     * @test
     */
    public function shouldGetLength()
    {
        // given
        $coordinates = new SubjectCoordinates(new ConstantEntry('śćł€', 2), new ThrowSubject());

        // when
        $length = $coordinates->characterLength();

        // then
        $this->assertSame(4, $length);
    }

    /**
     * @test
     */
    public function shouldGetByteLength()
    {
        // given
        $coordinates = new SubjectCoordinates(new ConstantEntry('śćł€', 2), new ThrowSubject());

        // when
        $length = $coordinates->byteLength();

        // then
        $this->assertSame(9, $length);
    }
}
