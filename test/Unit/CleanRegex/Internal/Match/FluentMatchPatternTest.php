<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\CustomException;
use Test\Utils\Functions;
use Test\Utils\Impl\AllStream;
use Test\Utils\Impl\ConstantInt;
use Test\Utils\Impl\EmptyStream;
use Test\Utils\Impl\FirstStream;
use Test\Utils\Impl\ThrowingOptionalWorker;
use Test\Utils\Impl\ThrowStream;
use Test\Utils\Impl\ThrowWorker;
use TRegx\CleanRegex\Exception\FluentMatchPatternException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Match\FluentMatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\FluentMatchPattern
 */
class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['foo', 'bar']), new ThrowWorker());

        // when
        $result = $pattern->all();

        // then
        $this->assertSame(['foo', 'bar'], $result);
    }

    /**
     * @test
     */
    public function shouldGetOnly()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['foo', 'bar', 'fail']), new ThrowWorker());

        // when
        $result = $pattern->only(2);

        // then
        $this->assertSame(['foo', 'bar'], $result);
    }

    /**
     * @test
     */
    public function shouldGetOnly_overflowing()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['foo', 'bar']), new ThrowWorker());

        // when
        $result = $pattern->only(4);

        // then
        $this->assertSame(['foo', 'bar'], $result);
    }

    /**
     * @test
     */
    public function shouldGetOnly_throw()
    {
        // given
        $pattern = new FluentMatchPattern(new ThrowStream(), new ThrowWorker());

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');

        // when
        $pattern->only(-2);
    }

    /**
     * @test
     */
    public function shouldIterate()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['foo', 'bar']), new ThrowWorker());

        // when
        $result = [];
        $pattern->forEach(function (string $input) use (&$result) {
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
        $pattern = new FluentMatchPattern(new AllStream(['foo', 'bar', 'lorem', 'b' => 'ipsum']), new ThrowWorker());

        // when
        $result = $pattern->count();

        // then
        $this->assertSame(4, $result);
    }

    /**
     * @test
     */
    public function shouldIterator()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['foo', 'bar', 'lorem', 'ipsum']), new ThrowWorker());

        // when
        $result = $pattern->getIterator();

        // then
        $this->assertSame(['foo', 'bar', 'lorem', 'ipsum'], iterator_to_array($result));
    }

    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['foo', 'foo', 'bar', 'foo', 'Bar', 'bar']), new ThrowWorker());

        // when
        $result = $pattern->map('strToUpper')->all();

        // then
        $this->assertSame(['FOO', 'FOO', 'BAR', 'FOO', 'BAR', 'BAR'], $result);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMap_all()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['foo', 'bar']), new ThrowWorker());

        // when
        $result = $pattern->flatMap('str_split')->all();

        // then
        $this->assertSame(['f', 'o', 'o', 'b', 'a', 'r'], $result);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMapAssoc_all()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['Quizzacious', 'Lorem', 'Foo']), new ThrowWorker());

        // when
        $result = $pattern->flatMapAssoc('str_split')->all();

        // then
        $this->assertSame(['F', 'o', 'o', 'e', 'm', 'a', 'c', 'i', 'o', 'u', 's'], $result);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMap_first()
    {
        // given
        $pattern = new FluentMatchPattern(new FirstStream('foo'), ThrowingOptionalWorker::none());

        // when
        $result = $pattern->flatMap(Functions::letters())->first();

        // then
        $this->assertSame('f', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_flatMap_keys_first()
    {
        // given
        $pattern = new FluentMatchPattern(new FirstStream('One'), ThrowingOptionalWorker::none());

        // when
        $result = $pattern->flatMap(Functions::lettersAsKeys())->keys()->first();

        // then
        $this->assertSame('O', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_flatMap_first_forEmpty()
    {
        // given
        $pattern = new FluentMatchPattern(new EmptyStream(), ThrowingOptionalWorker::fluent(new CustomException('message')));

        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('message');

        // when
        $pattern->flatMap(Functions::identity())->first();
    }

    /**
     * @test
     */
    public function shouldThrow_flatMap_keys_first_forEmpty()
    {
        // given
        $pattern = new FluentMatchPattern(new EmptyStream(), ThrowingOptionalWorker::fluent(new CustomException('message')));

        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('message');

        // when
        $pattern->flatMap(Functions::constant([]))->keys()->first();
    }

    /**
     * @test
     */
    public function shouldFilter()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['foo', 2, 'bar', 4]), new ThrowWorker());

        // when
        $result = $pattern->filter('is_int')->all();

        // then
        $this->assertSame([2, 4], $result);
    }

    /**
     * @test
     */
    public function shouldUnique()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['foo', 'foo', 'bar', 'foo', 'Bar', 'bar']), new ThrowWorker());

        // when
        $result = $pattern->distinct()->all();

        // then
        $this->assertSame(['foo', 2 => 'bar', 4 => 'Bar'], $result);
    }

    /**
     * @test
     */
    public function shouldGetValues()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream([10 => 'foo', 20 => 'bar', 30 => 'lorem']), new ThrowWorker());

        // when
        $result = $pattern->values()->all();

        // then
        $this->assertSame(['foo', 'bar', 'lorem'], $result);
    }

    /**
     * @test
     */
    public function shouldGetKeys()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream([10 => 'foo', 20 => 'bar', 30 => 'lorem']), new ThrowWorker());

        // when
        $result = $pattern->keys()->all();

        // then
        $this->assertSame([10, 20, 30], $result);
    }

    /**
     * @test
     */
    public function shouldMapToIntegers()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['a' => '9', '10', 'b' => 11, '100', 'c' => 12]), new ThrowWorker());

        // when
        $integers = $pattern->asInt()->all();

        // then
        $this->assertSame(['a' => 9, 10, 'b' => 11, 100, 'c' => 12], $integers);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidIntegers()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['9', '10', '--10', '100']), new ThrowWorker());

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse fluent element '--10', but it is not a valid integer");

        // when
        $pattern->asInt()->all();
    }

    /**
     * @test
     */
    public function shouldMapMatchesToIntegers()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['a' => new ConstantInt(9), 'b' => new ConstantInt(10)]), new ThrowWorker());

        // when
        $integers = $pattern->asInt()->all();

        // then
        $this->assertSame(['a' => 9, 'b' => 10], $integers);
    }

    /**
     * @test
     */
    public function shouldThrowForNonStringAndNonInt()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['9', true]), new ThrowWorker());

        // then
        $this->expectException(FluentMatchPatternException::class);
        $this->expectExceptionMessage("Invalid data types passed to asInt() method. Expected integer|string, but boolean (true) given");

        // when
        $pattern->asInt()->all();
    }

    /**
     * @test
     */
    public function shouldGroupByCallback()
    {
        // given
        $theSeven = ['Father', 'Mother', 'Maiden', 'Crone', 'Warrior', 'Smith', 'Stranger'];
        $pattern = new FluentMatchPattern(new AllStream($theSeven), new ThrowWorker());

        // when
        $result = $pattern->groupByCallback(Functions::charAt(0));

        // then
        $expected = [
            'F' => ['Father'],
            'M' => ['Mother', 'Maiden'],
            'C' => ['Crone'],
            'W' => ['Warrior'],
            'S' => ['Smith', 'Stranger'],
        ];
        $this->assertSame($expected, $result->all());
    }
}
