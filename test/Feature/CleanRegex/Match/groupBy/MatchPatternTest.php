<?php
namespace Test\Feature\TRegx\CleanRegex\Match\groupBy;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\GroupByPattern;

/**
 * @coversNothing
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGroupBy_texts()
    {
        // when
        $result = $this->groupBy()->all();

        // then
        $this->assertSame([
            'cm' => ['14cm', '19cm', '2cm'],
            'mm' => ['13mm', '18mm']
        ], $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_offsets()
    {
        // when
        $result = $this->groupBy()->offsets();

        // then
        $this->assertSame([
            'cm' => [5, 15, 25],
            'mm' => [10, 20],
        ], $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_byteOffsets()
    {
        // when
        $result = $this->groupBy()->byteOffsets();

        // then
        $this->assertSame([
            'cm' => [7, 17, 27],
            'mm' => [12, 22],
        ], $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_map()
    {
        // when
        $result = $this->groupBy()->map(function (Detail $detail) {
            return "$detail";
        });

        // then
        $this->assertSame([
            'cm' => ['14cm', '19cm', '2cm'],
            'mm' => ['13mm', '18mm'],
        ], $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_flatMap()
    {
        // when
        $result = $this->groupBy()->flatMap(function (Detail $detail) {
            return [$detail->offset() => "$detail", $detail->offset()];
        });

        // then
        $this->assertSame([
            'cm' => ['14cm', 5, '19cm', 15, '2cm', 25],
            'mm' => ['13mm', 10, '18mm', 20],
        ], $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_flatMapAssoc()
    {
        // when
        $result = $this->groupBy()->flatMapAssoc(function (Detail $detail) {
            return [$detail->offset() => "$detail"];
        });

        // then
        $this->assertSame([
            'cm' => [
                5  => '14cm',
                15 => '19cm',
                25 => '2cm',
            ],
            'mm' => [
                10 => '13mm',
                20 => '18mm',
            ],
        ], $result);
    }

    /**
     * @test
     */
    public function shouldNotIncludeFilteredOut_texts()
    {
        // given
        $groupByPattern = $this->filtered();

        // when
        $result = $groupByPattern->all();

        // then
        $this->assertSame(['cm' => ['19cm', '2cm'], 'mm' => ['18mm']], $result);
    }

    /**
     * @test
     * @dataProvider mappersWithMatch
     * @param string $function
     * @param array $expected
     */
    public function shouldNotIncludeFilteredOut(string $function, array $expected)
    {
        // given
        $groupByPattern = $this->filtered();

        // when
        $result = $groupByPattern->$function(function (Detail $detail) {
            return [$detail->text(), $detail->offset()];
        });

        // then
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     * @dataProvider mappersWithMatch
     * @param string $function
     */
    public function shouldNotHaveLimit(string $function)
    {
        // given
        $groupBy = $this->filtered();
        $called = [];

        // when
        $groupBy->$function(function (Detail $detail) use (&$called) {
            // when
            $called[] = $detail->text();
            $this->assertSame(-1, $detail->limit());

            // clean
            return [];
        });

        // then
        $this->assertSame(['19cm', '18mm', '2cm'], $called);
    }

    /**
     * @test
     * @dataProvider mappersWithMatch
     * @param string $function
     */
    public function shouldReturnOtherMatches_whenFiltered(string $function)
    {
        // given
        $groupByPattern = $this->filtered();

        // when
        $groupByPattern->$function(function (Detail $detail) {
            // when
            $this->assertSame(['12', '19cm', '18mm', '2cm'], $detail->all());

            // clean
            return [];
        });
    }

    /**
     * @test
     * @dataProvider mappersWithMatch
     * @param string $function
     */
    public function shouldPreserveUserData(string $function)
    {
        // given
        $groupByPattern = $this->filtered();

        // when
        $groupByPattern->$function(function (Detail $detail) {
            // when
            $this->assertSame("verify me:$detail", $detail->getUserData());

            // clean
            return [];
        });
    }

    /**
     * @test
     * @dataProvider mappersWithMatch
     * @param string $function
     */
    public function shouldIndexMatches(string $function)
    {
        // given
        $groupByPattern = $this->groupBy();
        $indexes = [];

        // when
        $groupByPattern->$function(function (Detail $detail) use (&$indexes) {
            // when
            $indexes[$detail->text()] = $detail->index();

            // clean
            return [];
        });

        // then
        $this->assertSame(['14cm' => 1, '13mm' => 2, '19cm' => 3, '18mm' => 4, '2cm' => 5], $indexes);
    }

    /**
     * @test
     * @dataProvider mappersWithMatch
     * @param string $function
     */
    public function shouldIndexMatches_filtered(string $function)
    {
        // given
        $groupByPattern = $this->filtered();
        $indexes = [];

        // when
        $groupByPattern->$function(function (Detail $detail) use (&$indexes) {
            // when
            $indexes[$detail->text()] = $detail->index();

            // clean
            return [];
        });

        // then
        $this->assertSame(['19cm' => 3, '18mm' => 4, '2cm' => 5], $indexes);
    }

    public function mappersWithMatch(): array
    {
        return [
            'map'     => ['map', [
                'cm' => [['19cm', 15], ['2cm', 25]],
                'mm' => [['18mm', 20]],
            ]],
            'flatMap' => ['flatMap', [
                'cm' => ['19cm', 15, '2cm', 25],
                'mm' => ['18mm', 20],
            ]],
        ];
    }

    private function groupBy(): GroupByPattern
    {
        return $this->match()->groupBy('unit');
    }

    private function filtered(): GroupByPattern
    {
        return $this->match()
            ->remaining(function (Detail $detail) {
                $detail->setUserData("verify me:$detail");
                return !in_array($detail->text(), ['14cm', '13mm']);
            })
            ->groupBy('unit');
    }

    private function match(): AbstractMatchPattern
    {
        return pattern('\d+(?<unit>cm|mm)?', 'u')->match('€12, 14cm 13mm 19cm 18mm 2cm');
    }
}
