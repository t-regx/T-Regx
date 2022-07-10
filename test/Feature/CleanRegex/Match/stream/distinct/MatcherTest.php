<?php
namespace Test\Feature\CleanRegex\Match\stream\distinct;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\Structure\Expect;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use AssertsDetail, AssertsStructure;

    /**
     * @test
     */
    public function shouldRemoveDuplicates()
    {
        // given
        $stream = Pattern::of('Foo')
            ->match('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant(['12', '12', 12, '12' => 13]));
        // when
        $distinct = $stream->distinct()->all();
        // then
        $this->assertSame(['12', 2 => 12, '12' => 13], $distinct);
    }

    /**
     * @test
     */
    public function shouldDistinctObjects()
    {
        // given
        $stream = Pattern::of('\w+')->match('One, Two, Three')->stream();
        // when
        [$one, $two, $three] = $stream->distinct()->all();
        // then
        $this->assertSame('One', $one->text());
        $this->assertSame('Two', $two->text());
        $this->assertSame('Three', $three->text());
    }

    /**
     * @test
     */
    public function shouldDistinctObjectsMultiple()
    {
        // given
        $stream = Pattern::of('\w+')->match('One, Two, Three')
            ->stream()
            ->flatMap(function (Detail $detail) {
                return [$detail, $detail];
            });
        // when
        $all = $stream->distinct()->all();
        // then
        $this->assertStructure($all, [
            0 => Expect::text('One'),
            2 => Expect::text('Two'),
            4 => Expect::text('Three')
        ]);
    }
}
