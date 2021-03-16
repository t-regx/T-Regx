<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\CustomException;
use Test\Utils\Functions;
use Test\Utils\ThrowWorker;
use TRegx\CleanRegex\Exception\FluentMatchPatternException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\Factory\Worker\NextStreamWorkerDecorator;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new FluentMatchPattern($this->all(['foo', 'bar']), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all(['foo', 'bar', 'fail']), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all(['foo', 'bar']), $this->worker());

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
        $pattern = new FluentMatchPattern($this->zeroInteraction(), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all(['foo', 'bar']), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all(['foo', 'bar', 'lorem', 'b' => 'ipsum']), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all(['foo', 'bar', 'lorem', 'ipsum']), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all(['foo', 'foo', 'bar', 'foo', 'Bar', 'bar']), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all(['foo', 'bar']), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all(['Quizzacious', 'Lorem', 'Foo']), $this->worker());

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
        $pattern = new FluentMatchPattern($this->method('first', 'foo'), ThrowWorker::none());

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
        $pattern = new FluentMatchPattern($this->method('first', 'One'), ThrowWorker::none());

        // when
        $result = $pattern->flatMap(Functions::lettersFlip())->keys()->first();

        // then
        $this->assertSame('O', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_flatMap_first_forEmpty()
    {
        // given
        $pattern = new FluentMatchPattern($this->empty(), ThrowWorker::fluent(new CustomException('message')));

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
        $pattern = new FluentMatchPattern($this->empty(), ThrowWorker::fluent(new CustomException('message')));

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
        $pattern = new FluentMatchPattern($this->all(['foo', 2, 'bar', 4]), $this->worker());

        // when
        $result = $pattern->filter('is_int')->all();

        // then
        $this->assertSame([1 => 2, 3 => 4], $result);
    }

    /**
     * @test
     */
    public function shouldUnique()
    {
        // given
        $pattern = new FluentMatchPattern($this->all(['foo', 'foo', 'bar', 'foo', 'Bar', 'bar']), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all([10 => 'foo', 20 => 'bar', 30 => 'lorem']), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all([10 => 'foo', 20 => 'bar', 30 => 'lorem']), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all(['a' => '9', '10', 'b' => 11, '100', 'c' => 12]), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all(['9', '10', '--10', '100']), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all(['a' => $this->match(9), 'b' => $this->match(10)]), $this->worker());

        // when
        $integers = $pattern->asInt()->all();

        // then
        $this->assertSame(['a' => 9, 'b' => 10], $integers);
    }

    /**
     * @test
     */
    public function shouldMapMatchGroupsToIntegers()
    {
        // given
        $pattern = new FluentMatchPattern($this->all(['a' => $this->matchGroup(9), 'b' => $this->matchGroup(10)]), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all(['9', true]), $this->worker());

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
        $pattern = new FluentMatchPattern($this->all($theSeven), $this->worker());

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

    private function worker(): NextStreamWorkerDecorator
    {
        return $this->createMock(NextStreamWorkerDecorator::class);
    }

    private function match(int $value): Detail
    {
        /** @var Detail|MockObject $mockObject */
        $mockObject = $this->createMock(Detail::class);
        $mockObject->method('toInt')->willReturn($value);
        return $mockObject;
    }

    private function matchGroup(int $value): Group
    {
        /** @var Group|MockObject $mockObject */
        $mockObject = $this->createMock(Group::class);
        $mockObject->method('toInt')->willReturn($value);
        return $mockObject;
    }

    private function all(array $return): Stream
    {
        return $this->method('all', $return);
    }

    private function empty(): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->once())->method('first')->willReturn([]);
        $stream->expects($this->once())->method('all')->willReturn([[], []]);
        return $stream;
    }

    private function method(string $methodName, $return = null): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->once())->method($methodName)->willReturn($return);
        $stream->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $stream;
    }

    private function zeroInteraction(): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->never())->method($this->anything());
        return $stream;
    }
}

