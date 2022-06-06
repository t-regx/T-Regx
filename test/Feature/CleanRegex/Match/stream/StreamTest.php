<?php
namespace Test\Feature\CleanRegex\Match\stream;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Match\Details\ConstantInt;
use Test\Utils\DetailFunctions;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\InvalidIntegerTypeException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 */
class StreamTest extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldGetOnlyOverflow()
    {
        // when
        $only = Pattern::alteration(['Two', 'One', 'Three'])->match('One, Two, Three')
            ->stream()
            ->map(DetailFunctions::text())
            ->limit(4)
            ->all();
        // then
        $this->assertSame(['One', 'Two', 'Three'], $only);
    }

    /**
     * @test
     */
    public function shouldIterate()
    {
        // given
        $stream = Pattern::alteration(['Two', 'One', 'Three'])->match('One, Two, Three')->stream()->map(DetailFunctions::text());
        // when
        $stream->forEach(Functions::collect($result));
        // then
        $this->assertSame(['One', 'Two', 'Three'], $result);
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        $stream = Pattern::of('\w+')->match('One, Two, Three, Four')->stream();
        // when
        $count = $stream->count();
        // then
        $this->assertSame(4, $count);
    }

    /**
     * @test
     */
    public function shouldBeCountable()
    {
        // given
        $stream = Pattern::of('\w+')->match('One, Two, Three, Four')->stream();
        // when
        $count = \count($stream);
        // then
        $this->assertSame(4, $count);
    }

    /**
     * @test
     */
    public function shouldGetIterator()
    {
        // given
        $stream = Pattern::of('\w+')->match('foo, bar, lorem, ipsum')->stream()->map(DetailFunctions::text());
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
        $stream = Pattern::of('\w+')->match('foo, bar, lorem, ipsum')->stream();
        // when
        $mapped = $stream->map('strToUpper')->all();
        // then
        $this->assertSame(['FOO', 'BAR', 'LOREM', 'IPSUM'], $mapped);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMap_all()
    {
        // given
        $stream = Pattern::of('\w+')->match('Foo, Bar')->stream();
        // when
        $flatMapped = $stream->flatMap(Functions::letters())->all();
        // then
        $this->assertSame(['F', 'o', 'o', 'B', 'a', 'r'], $flatMapped);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMapAssoc_all()
    {
        // given
        $stream = Pattern::of('\w+')->match('Quizzacious, Lorem, Foo')->stream()->map(DetailFunctions::text());
        // when
        $flatMapped = $stream->flatMapAssoc(Functions::letters())->all();
        // then
        $this->assertSame(['F', 'o', 'o', 'e', 'm', 'a', 'c', 'i', 'o', 'u', 's'], $flatMapped);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMap_first()
    {
        // given
        $stream = Pattern::literal('Foo')->match('Foo')->stream();
        // when
        $firstFlatMapped = $stream->flatMap(Functions::letters())->first();
        // then
        $this->assertSame('F', $firstFlatMapped);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMap_nth()
    {
        // given
        $stream = Pattern::of('\w+')->match('Bar, Cat')->stream();
        // when
        $flatMapped = $stream->flatMap(Functions::letters())->nth(3);
        // then
        $this->assertSame('C', $flatMapped);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMap_keys_first()
    {
        // given
        $stream = Pattern::of('\w+')->match('Bar, Cat')->stream();
        // when
        $flatMappedKey = $stream->flatMapAssoc(Functions::lettersAsKeys())->keys()->first();
        // then
        $this->assertSame('B', $flatMappedKey);
    }

    /**
     * @test
     */
    public function shouldFilter()
    {
        // given
        $stream = Pattern::of('Foo')->match('Foo')->stream()->flatMap(Functions::constant(['foo', 2, 'bar', 4]));
        // when
        $filtered = $stream->filter('is_int')->all();
        // then
        $this->assertSame([1 => 2, 3 => 4], $filtered);
    }

    /**
     * @test
     */
    public function shouldReturnUniqueElements()
    {
        // given
        $stream = Pattern::of('\w+')->match('foo, foo, bar, foo, Bar, bar')->stream()->map(DetailFunctions::text());
        // when
        $distinct = $stream->distinct()->all();
        // then
        $this->assertSame(['foo', 2 => 'bar', 4 => 'Bar'], $distinct);
    }

    /**
     * @test
     */
    public function shouldGetValues()
    {
        // given
        $stream = Pattern::literal('Foo')
            ->match('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant([10 => 'foo', 20 => 'bar', 30 => 'lorem']));
        // when
        $values = $stream->values()->all();
        // then
        $this->assertSame(['foo', 'bar', 'lorem'], $values);
    }

    /**
     * @test
     */
    public function shouldGetKeys()
    {
        // given
        $stream = Pattern::literal('Foo')
            ->match('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant([10 => 'foo', 20 => 'bar', 30 => 'lorem']));
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
        $stream = Pattern::literal('Foo')
            ->match('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant(['a' => '9', '10', 'b' => 11, '100', 'c' => 12]));
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
        $stream = Pattern::literal('Foo')
            ->match('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant($input));
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
            [[new ConstantInt(12, 37)]],
            [[1, 2, 3]],
        ];
    }

    /**
     * @test
     */
    public function shouldMapToIntegersBase5()
    {
        // given
        $stream = Pattern::literal('Foo')->match('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant(['a' => '123']));
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
        $stream = Pattern::of('-*\d+')->match('9, 10, --10, 100')
            ->stream()
            ->map(DetailFunctions::text());
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse stream element '--10', but it is not a valid integer in base 10");
        // when
        $stream->asInt()->all();
    }

    /**
     * @test
     */
    public function shouldMapMatchesToIntegers()
    {
        // given
        $values = ['a' => new ConstantInt(9, 10), 'b' => new ConstantInt(10, 10)];
        $stream = Pattern::of('Foo')->match('Foo')->stream()->flatMapAssoc(Functions::constant($values));
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
        $stream = Pattern::of('Foo')->match('Foo')->stream()->flatMapAssoc(Functions::constant(['9', true]));
        // then
        $this->expectException(InvalidIntegerTypeException::class);
        $this->expectExceptionMessage('Failed to parse value as integer. Expected integer|string, but boolean (true) given');
        // when
        $stream->asInt()->all();
    }

    /**
     * @test
     */
    public function shouldGroupByCallback()
    {
        // given
        $stream = Pattern::of('\w+')->match('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger')->stream()->map(DetailFunctions::text());
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
        $stream = Pattern::of('Foo')->match('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant(['Foo' => '9', 2 => 'Bar']));
        // when
        $stream->forEach(Functions::collectAsEntries($arguments));
        // then
        $this->assertSame(['9' => 'Foo', 'Bar' => 2], $arguments);
    }
}
