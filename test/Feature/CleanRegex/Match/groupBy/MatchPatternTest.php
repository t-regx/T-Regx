<?php
namespace Test\Feature\TRegx\CleanRegex\Match\groupBy;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\NonNestedValueException;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\GroupByPattern;
use TRegx\CleanRegex\Match\MatchPattern;

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
     * @dataProvider mappersWithMatch
     * @param string $function
     */
    public function shouldIndexMatches(string $function)
    {
        // given
        $groupByPattern = $this->groupBy();
        $indexes = [];

        // when
        $groupByPattern->$function(DetailFunctions::collect($indexes, []));

        // then
        $this->assertSame(\array_flip(['14cm' => 1, '13mm' => 2, '19cm' => 3, '18mm' => 4, '2cm' => 5]), $indexes);
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

    /**
     * @test
     */
    public function shouldThrowForNonexistentGroup_byteOffsets()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'bar'");

        // when
        pattern('(?<foo>foo)')
            ->match('foo')
            ->groupBy('bar')
            ->byteOffsets();
    }

    /**
     * @test
     */
    public function shouldThrowForNonexistentGroup_map()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'cat'");

        // when
        pattern('(?<foo>foo)')
            ->match('foo')
            ->groupBy('cat')
            ->map(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldThrow_forFlatMap_forInvalidReturnType()
    {
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid flatMap() callback return type. Expected array, but integer (4) given');

        // when
        $this->groupBy()->flatMap(Functions::constant(4));
    }

    /**
     * @test
     */
    public function shouldNotSilenceInternalException_flatMap()
    {
        // then
        $this->expectException(NonNestedValueException::class);

        // when
        $this->groupBy()->flatMap(Functions::throws(new NonNestedValueException(new ValueType(null))));
    }

    /**
     * @test
     */
    public function shouldNotSilenceInternalException_flatMapAssoc()
    {
        // then
        $this->expectException(NonNestedValueException::class);

        // when
        $this->groupBy()->flatMapAssoc(Functions::throws(new NonNestedValueException(new ValueType(null))));
    }

    /**
     * @test
     */
    public function shouldThrow_forFlatMapAssoc_forInvalidReturnType()
    {
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid flatMapAssoc() callback return type. Expected array, but integer (4) given');

        // when
        $this->groupBy()->flatMapAssoc(Functions::constant(4));
    }

    private function groupBy(): GroupByPattern
    {
        return $this->match()->groupBy('unit');
    }

    private function match(): MatchPattern
    {
        return pattern('\d+(?<unit>cm|mm)?')->match('€12, 14cm 13mm 19cm 18mm 2cm');
    }
}
