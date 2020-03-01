<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\FluentMatchPatternException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedWorker;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new FluentMatchPattern(['foo', 'bar'], $this->mock());

        // when
        $result = $pattern->all();

        // then
        $this->assertEquals(['foo', 'bar'], $result);
    }

    /**
     * @test
     */
    public function shouldGetOnly()
    {
        // given
        $pattern = new FluentMatchPattern(['foo', 'bar', 'fail'], $this->mock());

        // when
        $result = $pattern->only(2);

        // then
        $this->assertEquals(['foo', 'bar'], $result);
    }

    /**
     * @test
     */
    public function shouldGetOnly_overflowing()
    {
        // given
        $pattern = new FluentMatchPattern(['foo', 'bar'], $this->mock());

        // when
        $result = $pattern->only(4);

        // then
        $this->assertEquals(['foo', 'bar'], $result);
    }

    /**
     * @test
     */
    public function shouldGetOnly_throw()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->mock());

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit -2');

        // when
        $pattern->only(-2);
    }

    /**
     * @test
     */
    public function shouldIterate()
    {
        // given
        $pattern = new FluentMatchPattern(['foo', 'bar'], $this->mock());

        // when
        $result = [];
        $pattern->forEach(function (string $input) use (&$result) {
            $result[] = $input;
        });

        // then
        $this->assertEquals(['foo', 'bar'], $result);
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        $pattern = new FluentMatchPattern(['foo', 'bar', 'lorem', 'ipsum'], $this->mock());

        // when
        $result = $pattern->count();

        // then
        $this->assertEquals(4, $result);
    }

    /**
     * @test
     */
    public function shouldIterator()
    {
        // given
        $pattern = new FluentMatchPattern(['foo', 'bar', 'lorem', 'ipsum'], $this->mock());

        // when
        $result = $pattern->iterator();

        // then
        $this->assertEquals(['foo', 'bar', 'lorem', 'ipsum'], iterator_to_array($result));
    }

    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $pattern = new FluentMatchPattern(['foo', 'foo', 'bar', 'foo', 'Bar', 'bar'], $this->mock());

        // when
        $result = $pattern->map('strtoupper')->all();

        // then
        $this->assertEquals(['FOO', 'FOO', 'BAR', 'FOO', 'BAR', 'BAR'], $result);
    }

    /**
     * @test
     */
    public function shouldFlatMap()
    {
        // given
        $pattern = new FluentMatchPattern(['foo', 'bar'], $this->mock());

        // when
        $result = $pattern->flatMap('str_split')->all();

        // then
        $this->assertEquals(['f', 'o', 'o', 'b', 'a', 'r'], $result);
    }

    /**
     * @test
     */
    public function shouldFlatMap_throw()
    {
        // given
        $pattern = new FluentMatchPattern(['foo', 'bar'], $this->mock());

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage("Invalid flatMap() callback return type. Expected array, but integer (0) given");

        // when
        $pattern->flatMap(function (string $input) {
            return 0;
        });
    }

    /**
     * @test
     */
    public function shouldFilter()
    {
        // given
        $pattern = new FluentMatchPattern(['foo', 2, 'bar', 4], $this->mock());

        // when
        $result = $pattern->filter('is_int')->all();

        // then
        $this->assertEquals([2, 4], $result);
    }

    /**
     * @test
     */
    public function shouldUnique()
    {
        // given
        $pattern = new FluentMatchPattern(['foo', 'foo', 'bar', 'foo', 'Bar', 'bar'], $this->mock());

        // when
        $result = $pattern->distinct()->all();

        // then
        $this->assertEquals(['foo', 'bar', 'Bar'], $result);
    }

    /**
     * @test
     */
    public function shouldGetValues()
    {
        // given
        $pattern = new FluentMatchPattern([10 => 'foo', 20 => 'bar', 30 => 'lorem'], $this->mock());

        // when
        $result = $pattern->values()->all();

        // then
        $this->assertEquals(['foo', 'bar', 'lorem'], $result);
    }

    /**
     * @test
     */
    public function shouldGetKeys()
    {
        // given
        $pattern = new FluentMatchPattern([10 => 'foo', 20 => 'bar', 30 => 'lorem'], $this->mock());

        // when
        $result = $pattern->keys()->all();

        // then
        $this->assertEquals([10, 20, 30], $result);
    }

    /**
     * @test
     */
    public function shouldMapToIntegers()
    {
        // given
        $pattern = new FluentMatchPattern(['a' => '9', '10', 'b' => 11, '100', 'c' => 12], $this->mock());

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
        $pattern = new FluentMatchPattern(['9', '10', '--10', '100'], $this->mock());

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse '--10', but it is not a valid integer");

        // when
        $pattern->asInt();
    }

    /**
     * @test
     */
    public function shouldThrowForNonStringAndNonInt()
    {
        // given
        $pattern = new FluentMatchPattern(['9', true], $this->mock());

        // then
        $this->expectException(FluentMatchPatternException::class);
        $this->expectExceptionMessage("Invalid data types passed to `asInt()` method. Expected 'string' or 'int', but boolean (true) given");

        // when
        $pattern->asInt();
    }

    /**
     * @test
     */
    public function shouldGroupBy()
    {
        // given
        $pattern = new FluentMatchPattern(['Father', 'Mother', 'Maiden', 'Crone', 'Warrior', 'Smith', 'Stranger'], $this->mock());

        // when
        $result = $pattern->groupByCallback(function (string $fucker) {
            return $fucker[0];
        });

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

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupByType()
    {
        // given
        $pattern = new FluentMatchPattern([''], $this->mock());

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid groupByCallback() callback return type. Expected int|string, but array (0) given');

        // when
        $pattern->groupByCallback(function () {
            return [];
        });
    }

    private function mock(): NotMatchedWorker
    {
        /** @var NotMatchedWorker $mockObject */
        $mockObject = $this->createMock(NotMatchedWorker::class);
        return $mockObject;
    }
}
