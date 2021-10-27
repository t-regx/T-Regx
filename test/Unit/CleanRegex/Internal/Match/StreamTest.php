<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Match\Stream\FirstStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\ThrowStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\Upstream\AllStream;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Test\Fakes\CleanRegex\Match\Details\ConstantInt;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\InvalidIntegerTypeException;
use TRegx\CleanRegex\Match\Stream;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = new Stream(new AllStream(['foo', 'bar']), new ThrowSubject());

        // when
        $values = $stream->all();

        // then
        $this->assertSame(['foo', 'bar'], $values);
    }

    /**
     * @test
     */
    public function shouldGetOnly()
    {
        // given
        $stream = new Stream(new AllStream(['foo', 'bar', 'fail']), new ThrowSubject());

        // when
        $only = $stream->only(2);

        // then
        $this->assertSame(['foo', 'bar'], $only);
    }

    /**
     * @test
     */
    public function shouldGetOnly_overflowing()
    {
        // given
        $stream = new Stream(new AllStream(['foo', 'bar']), new ThrowSubject());

        // when
        $only = $stream->only(4);

        // then
        $this->assertSame(['foo', 'bar'], $only);
    }

    /**
     * @test
     */
    public function shouldGetOnly_throw()
    {
        // given
        $stream = new Stream(new ThrowStream(), new ThrowSubject());

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');

        // when
        $stream->only(-2);
    }

    /**
     * @test
     */
    public function shouldIterate()
    {
        // given
        $stream = new Stream(new AllStream(['foo', 'bar']), new ThrowSubject());

        // when
        $result = [];
        $stream->forEach(function (string $input) use (&$result) {
            $result[] = $input;
        });

        // then
        $this->assertSame(['foo', 'bar'], $result);
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        $stream = new Stream(new AllStream(['foo', 'bar', 'lorem', 'b' => 'ipsum']), new ThrowSubject());

        // when
        $count = $stream->count();

        // then
        $this->assertSame(4, $count);
    }

    /**
     * @test
     */
    public function shouldIterator()
    {
        // given
        $stream = new Stream(new AllStream(['foo', 'bar', 'lorem', 'ipsum']), new ThrowSubject());

        // when
        $iterator = $stream->getIterator();

        // then
        $this->assertSame(['foo', 'bar', 'lorem', 'ipsum'], \iterator_to_array($iterator));
    }

    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $stream = new Stream(new AllStream(['foo', 'foo', 'bar', 'foo', 'Bar', 'bar']), new ThrowSubject());

        // when
        $upper = $stream->map('strToUpper')->all();

        // then
        $this->assertSame(['FOO', 'FOO', 'BAR', 'FOO', 'BAR', 'BAR'], $upper);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMap_all()
    {
        // given
        $stream = new Stream(new AllStream(['foo', 'bar']), new ThrowSubject());

        // when
        $flatMapped = $stream->flatMap('str_split')->all();

        // then
        $this->assertSame(['f', 'o', 'o', 'b', 'a', 'r'], $flatMapped);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMapAssoc_all()
    {
        // given
        $stream = new Stream(new AllStream(['Quizzacious', 'Lorem', 'Foo']), new ThrowSubject());

        // when
        $flatMapped = $stream->flatMapAssoc('str_split')->all();

        // then
        $this->assertSame(['F', 'o', 'o', 'e', 'm', 'a', 'c', 'i', 'o', 'u', 's'], $flatMapped);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMap_first()
    {
        // given
        $stream = new Stream(new FirstStream('foo'), new ThrowSubject());

        // when
        $firstFlatMapped = $stream->flatMap(Functions::letters())->first();

        // then
        $this->assertSame('f', $firstFlatMapped);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMap_nth()
    {
        // given
        $stream = new Stream(new AllStream(['bar', 'cat']), new ThrowSubject());

        // when
        $flatMapped = $stream->flatMap(Functions::letters())->nth(3);

        // then
        $this->assertSame('c', $flatMapped);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMap_keys_first()
    {
        // given
        $stream = new Stream(new FirstStream('One'), new ThrowSubject());

        // when
        $flatMappedKey = $stream->flatMap(Functions::lettersAsKeys())->keys()->first();

        // then
        $this->assertSame('O', $flatMappedKey);
    }

    /**
     * @test
     */
    public function shouldFilter()
    {
        // given
        $stream = new Stream(new AllStream(['foo', 2, 'bar', 4]), new ThrowSubject());

        // when
        $filtered = $stream->filter('is_int')->all();

        // then
        $this->assertSame([2, 4], $filtered);
    }

    /**
     * @test
     */
    public function shouldUnique()
    {
        // given
        $stream = new Stream(new AllStream(['foo', 'foo', 'bar', 'foo', 'Bar', 'bar']), new ThrowSubject());

        // when
        $result = $stream->distinct()->all();

        // then
        $this->assertSame(['foo', 2 => 'bar', 4 => 'Bar'], $result);
    }

    /**
     * @test
     */
    public function shouldGetValues()
    {
        // given
        $stream = new Stream(new AllStream([10 => 'foo', 20 => 'bar', 30 => 'lorem']), new ThrowSubject());

        // when
        $result = $stream->values()->all();

        // then
        $this->assertSame(['foo', 'bar', 'lorem'], $result);
    }

    /**
     * @test
     */
    public function shouldGetKeys()
    {
        // given
        $stream = new Stream(new AllStream([10 => 'foo', 20 => 'bar', 30 => 'lorem']), new ThrowSubject());

        // when
        $keys = $stream->keys()->all();

        // then
        $this->assertSame([10, 20, 30], $keys);
    }

    /**
     * @test
     */
    public function shouldMapToIntegers()
    {
        // given
        $stream = new Stream(new AllStream(['a' => '9', '10', 'b' => 11, '100', 'c' => 12]), new ThrowSubject());

        // when
        $integers = $stream->asInt()->all();

        // then
        $this->assertSame(['a' => 9, 10, 'b' => 11, 100, 'c' => 12], $integers);
    }

    /**
     * @test
     * @dataProvider inputs
     */
    public function shouldThrowForInvalidBase(array $input)
    {
        // given
        $stream = new Stream(new AllStream($input), new ThrowSubject());

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: 37 (supported bases 2-36, case-insensitive)');

        // when
        $stream->asInt(37)->all();
    }

    public function inputs(): array
    {
        return [
            [[]],
            [[new ConstantInt(12)]],
            [[1, 2, 3]],
        ];
    }

    /**
     * @test
     */
    public function shouldMapToIntegersBase5()
    {
        // given
        $stream = new Stream(new AllStream(['a' => '123']), new ThrowSubject());

        // when
        $integers = $stream->asInt(5)->all();

        // then
        $this->assertSame(['a' => 38], $integers);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidIntegers()
    {
        // given
        $stream = new Stream(new AllStream(['9', '10', '--10', '100']), new ThrowSubject());

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse fluent element '--10', but it is not a valid integer");

        // when
        $stream->asInt()->all();
    }

    /**
     * @test
     */
    public function shouldMapMatchesToIntegers()
    {
        // given
        $stream = new Stream(new AllStream(['a' => new ConstantInt(9), 'b' => new ConstantInt(10)]), new ThrowSubject());

        // when
        $integers = $stream->asInt()->all();

        // then
        $this->assertSame(['a' => 9, 'b' => 10], $integers);
    }

    /**
     * @test
     */
    public function shouldThrowForNonStringAndNonInt()
    {
        // given
        $stream = new Stream(new AllStream(['9', true]), new ThrowSubject());

        // then
        $this->expectException(InvalidIntegerTypeException::class);
        $this->expectExceptionMessage("Failed to parse value as integer. Expected integer|string, but boolean (true) given");

        // when
        $stream->asInt()->all();
    }

    /**
     * @test
     */
    public function shouldGroupByCallback()
    {
        // given
        $theSeven = ['Father', 'Mother', 'Maiden', 'Crone', 'Warrior', 'Smith', 'Stranger'];
        $stream = new Stream(new AllStream($theSeven), new ThrowSubject());

        // when
        $grouppedBy = $stream->groupByCallback(Functions::charAt(0));

        // then
        $expected = [
            'F' => ['Father'],
            'M' => ['Mother', 'Maiden'],
            'C' => ['Crone'],
            'W' => ['Warrior'],
            'S' => ['Smith', 'Stranger'],
        ];
        $this->assertSame($expected, $grouppedBy->all());
    }

    /**
     * @test
     */
    public function shouldForEach_acceptKey()
    {
        // given
        $stream = new Stream(new AllStream(['Foo' => '9', 2 => 'Bar']), new ThrowSubject());
        $arguments = [];

        // when
        $stream->forEach(function ($argument, $key) use (&$arguments) {
            $arguments[$argument] = $key;
        });

        // then
        $this->assertSame(['9' => 'Foo', 'Bar' => 2], $arguments);
    }
}
