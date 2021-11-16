<?php
namespace Test\Feature\TRegx\CleanRegex\Match\groupBy;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\GroupByPattern;

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
        $expected = [
            'cm' => ['14cm', '19cm', '2cm'],
            'mm' => ['13mm', '18mm']
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_offsets()
    {
        // when
        $result = $this->groupBy()->offsets();

        // then
        $expected = [
            'cm' => [5, 15, 25],
            'mm' => [10, 20],
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function shouldGroupBy_byteOffsets()
    {
        // when
        $result = $this->groupBy()->byteOffsets();

        // then
        $expected = [
            'cm' => [7, 17, 27],
            'mm' => [12, 22],
        ];
        $this->assertSame($expected, $result);
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
        $expected = [
            'cm' => ['14cm', '19cm', '2cm'],
            'mm' => ['13mm', '18mm'],
        ];
        $this->assertSame($expected, $result);
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
        $expected = [
            'cm' => ['14cm', 5, '19cm', 15, '2cm', 25],
            'mm' => ['13mm', 10, '18mm', 20],
        ];
        $this->assertSame($expected, $result);
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
        $expected = [
            'cm' => [
                5  => '14cm',
                15 => '19cm',
                25 => '2cm',
            ],
            'mm' => [
                10 => '13mm',
                20 => '18mm',
            ],
        ];
        $this->assertSame($expected, $result);
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
        $this->match()
            ->remaining(function (Detail $detail) {
                // when
                $detail->setUserData("user data:$detail");
                return true;
            })
            ->groupBy('unit')
            ->$function(function (Detail $detail) {
                // then
                $this->assertSame("user data:$detail", $detail->getUserData());

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
        $groupByPattern->$function(Functions::collect($indexes, []));

        // then
        $this->assertSame(\array_flip(['14cm' => 1, '13mm' => 2, '19cm' => 3, '18mm' => 4, '2cm' => 5]), $indexes);
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
            ->remaining(Functions::oneOf(['12', '19cm', '18mm', '2cm']))
            ->groupBy('unit');
    }

    private function match(): AbstractMatchPattern
    {
        return pattern('\d+(?<unit>cm|mm)?')->match('€12, 14cm 13mm 19cm 18mm 2cm');
    }
}
