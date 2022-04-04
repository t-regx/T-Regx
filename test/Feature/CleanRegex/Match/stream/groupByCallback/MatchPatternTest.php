<?php
namespace Test\Feature\TRegx\CleanRegex\Match\stream\groupByCallback;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\CausesBacktracking;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Stream;
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
}
