<?php
namespace Test\Feature\TRegx\CleanRegex\Match\groupBy;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\GroupByPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider mappers
     * @param string $function
     * @param array $arguments
     */
    public function shouldGroup(string $function, array $arguments)
    {
        // given
        $pattern = $this->groupBy();

        // when
        $result = $pattern->$function(...$arguments);

        // then
        $this->assertEquals([
            'cm' => ['14cm', '19cm', '2cm'],
            'mm' => ['13mm', '18mm']
        ], $result);
    }

    /**
     * @test
     * @dataProvider mappers
     * @param string $function
     * @param array $arguments
     */
    public function shouldNotIncludeFilteredOut(string $function, array $arguments)
    {
        // given
        $groupByPattern = $this->filtered();

        // when
        $result = $groupByPattern->$function(...$arguments);

        // then
        $this->assertEquals([
            'cm' => ['19cm', '2cm'],
            'mm' => ['18mm'],
        ], $result);
    }

    public function mappers(): array
    {
        return array_merge(
            ['texts' => ['texts', []]],
            $this->mappersWithMatch()
        );
    }

    /**
     * @test
     * @dataProvider mappersWithMatch
     * @param string $function
     * @param array $arguments
     */
    public function shouldNotHaveLimit(string $function, array $arguments)
    {
        // given
        $groupBy = $this->filtered();
        $called = 0;

        // when
        $groupBy->$function(function (Match $match) use (&$called) {
            $called++;
            $this->assertEquals(-1, $match->limit());
        });

        // then
        $this->assertEquals(3, $called, "Failed to assert that $function() was called 3 times");
        // There are 6 entries: '12' doesn't have 'unit' group matched, '14cm' and '13mm' are filtered out, 3 are left
    }

    /**
     * @test
     * @dataProvider mappersWithMatch
     * @param string $function
     * @param array $arguments
     */
    public function shouldReturnOtherMatches_whenFiltered(string $function, array $arguments)
    {
        // given
        $groupByPattern = $this->filtered();

        // when
        $groupByPattern->$function(function (Match $match) {
            $this->assertEquals(['12', '19cm', '18mm', '2cm'], $match->all());
        });
    }

    /**
     * @test
     * @dataProvider mappersWithMatch
     * @param string $function
     * @param array $arguments
     */
    public function shouldPreserveUserData(string $function, array $arguments)
    {
        // given
        $groupByPattern = $this->filtered();

        // when
        $groupByPattern->$function(function (Match $match) {
            $this->assertEquals("verify me:$match", $match->getUserData());
        });
    }

    /**
     * @test
     * @dataProvider mappersWithMatch
     * @param string $function
     * @param array $arguments
     */
    public function shouldIndexMatches(string $function, array $arguments)
    {
        // given
        $groupByPattern = $this->groupBy();
        $indexes = [];

        // when
        $groupByPattern->$function(function (Match $match) use (&$indexes) {
            // when
            $indexes[$match->text()] = $match->index();
        });

        // then
        $this->assertEquals(['14cm' => 1, '13mm' => 2, '19cm' => 3, '18mm' => 4, '2cm' => 5], $indexes);
    }

    /**
     * @test
     * @dataProvider mappersWithMatch
     * @param string $function
     * @param array $arguments
     */
    public function shouldIndexMatches_filtered(string $function, array $arguments)
    {
        // given
        $groupByPattern = $this->filtered();
        $indexes = [];

        // when
        $groupByPattern->$function(function (Match $match) use (&$indexes) {
            // when
            $indexes[$match->text()] = $match->index();
        });

        // then
        $this->assertEquals(['19cm' => 1, '18mm' => 2, '2cm' => 3], $indexes);
    }

    public function mappersWithMatch(): array
    {
        return [
            'map' => ['map', [function (Match $match) {
                return "$match";
            }]],
        ];
    }

    private function groupBy(): GroupByPattern
    {
        return $this->match()
            ->groupBy('unit');
    }

    private function filtered(): GroupByPattern
    {
        return $this
            ->match()
            ->filter(function (Match $match) {
                $match->setUserData("verify me:$match");
                return !in_array($match->text(), ['14cm', '13mm']);
            })
            ->groupBy('unit');
    }

    private function match(): AbstractMatchPattern
    {
        return pattern('\d+(?<unit>cm|mm)?')->match('12, 14cm 13mm 19cm 18mm 2cm');
    }
}
