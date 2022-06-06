<?php
namespace Test\Feature\CleanRegex\Match\stream\groupByCallback;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Match\Details\Group\TextGroup;
use Test\Fakes\CleanRegex\Match\Details\TextDetail;
use Test\Utils\AssertsSameMatches;
use Test\Utils\CausesBacktracking;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Stream;
use TRegx\CleanRegex\Pattern;
use function pattern;

class MatchPatternTest extends TestCase
{
    use AssertsSameMatches, CausesBacktracking;

    /**
     * @test
     */
    public function shouldGroupBy_callback()
    {
        // given
        $subject = '12cm 14mm 13cm 19cm 18mm 2mm';

        // when
        $result = $this->streamGroupByCallback($subject)->all();

        // then
        $expected = [
            'cm' => ['12cm', '13cm', '19cm'],
            'mm' => ['14mm', '18mm', '2mm']
        ];
        $this->assertSameMatches($expected, $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_callback_keys_all()
    {
        // given
        $subject = '12cm 14mm 13cm 19cm 18mm 2mm';

        // when
        $result = $this->streamGroupByCallback($subject)->keys()->all();

        // then
        $this->assertSame(['cm', 'mm'], $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_callback_keys_first()
    {
        // given
        $subject = '12cm 14mm 13cm 19cm 18mm 2mm';

        // when
        $result = $this->streamGroupByCallback($subject)->keys()->first();

        // then
        $this->assertSame('cm', $result);
    }

    private function streamGroupByCallback(string $subject): Stream
    {
        return pattern('(?<value>\d+)(?<unit>cm|mm)')
            ->match($subject)
            ->stream()
            ->groupByCallback(function (Detail $detail) {
                return $detail->get('unit');
            });
    }

    /**
     * @test
     */
    public function shouldGroupByIntegerValues()
    {
        // when
        $result = pattern('(?<value>\d+)(?<unit>cm|mm)')
            ->match('12cm 14mm 2cm 19cm 12mm 2mm')
            ->stream()
            ->groupByCallback(function (Detail $detail) {
                return $detail->group('value')->toInt();
            })
            ->all();

        // then
        $expected = [
            12 => ['12cm', '12mm'],
            14 => ['14mm'],
            2  => ['2cm', '2mm'],
            19 => ['19cm'],
        ];
        $this->assertSameMatches($expected, $result);
    }

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
