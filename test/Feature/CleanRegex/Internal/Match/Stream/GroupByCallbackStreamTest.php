<?php
namespace Test\Feature\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Match\Details\Group\TextGroup;
use Test\Fakes\CleanRegex\Match\Details\TextDetail;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\GroupByCallbackStream
 */
class GroupByCallbackStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = Pattern::of('\w+')->match('One, Two, Three')
            ->stream()
            ->map(DetailFunctions::text())
            ->groupByCallback(Functions::charAt(0));
        // when
        $all = $stream->all();
        // then
        $this->assertSame(['O' => ['One'], 'T' => ['Two', 'Three']], $all);
    }

    /**
     * @test
     */
    public function shouldGroupDifferentDataTypes()
    {
        // given
        $stream = Pattern::of('.')->match('Lorem')->stream()
            ->map(DetailFunctions::index())
            ->map(Functions::from(['hello', 2, new TextDetail('hello'), 2, new TextGroup('hello')]))
            ->groupByCallback(Functions::identity());
        // when
        $all = $stream->all();
        // then
        $expected = [
            'hello' => ['hello', new TextDetail('hello'), new TextGroup('hello')],
            2       => [2, 2],
        ];
        $this->assertEquals($expected, $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $stream = Pattern::of('\w+')->match('One,Two')->stream()->map(DetailFunctions::text())->groupByCallback(Functions::identity());
        // when
        $first = $stream->first();
        // then
        $this->assertSame('One', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = Pattern::of('One')->match('One,One')->stream()->map(DetailFunctions::text())->groupByCallback('strToUpper');
        // when
        $first = $stream->keys()->first();
        // then
        $this->assertSame('ONE', $first);
    }

    /**
     * @test
     */
    public function shouldThrow_first()
    {
        // given
        $stream = Pattern::of('Fail')->match('Match')->stream()->groupByCallback(Functions::fail());
        // then
        $this->expectException(NoSuchStreamElementException::class);
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupByType_all()
    {
        // given
        $stream = Pattern::of('Foo')->match('Foo')->stream()->groupByCallback(Functions::constant([]));
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid groupByCallback() callback return type. Expected int|string, but array (0) given');
        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupByType_first()
    {
        // given
        $stream = Pattern::of('Foo')->match('Foo')->stream()->groupByCallback(Functions::constant([]));
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid groupByCallback() callback return type. Expected int|string, but array (0) given');
        // when
        $stream->first();
    }
}
