<?php
namespace Test\Feature\TRegx\CleanRegex\Match\tuples;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnTuple()
    {
        // when
        [$value, $unit] = pattern('(\d+)(?<unit>cm|mm)')->match('12cm 14mm')->tuple(1, 'unit');

        // then
        $this->assertEquals(12, $value);
        $this->assertEquals('cm', $unit);
    }

    /**
     * @test
     */
    public function shouldReturnTriple()
    {
        // when
        [$a, $b, $c] = pattern('([ab])([12])([$%])')->match('a1% b2$')->triple(1, 3, 2);

        // then
        $this->assertEquals('a', $a);
        $this->assertEquals('%', $b);
        $this->assertEquals('1', $c);
    }

    /**
     * @test
     */
    public function shouldTupleCoalesceToNull()
    {
        // when
        [$o, $k] = pattern('(o)?(k)?')->match('')->tuple(1, 2);

        // then
        $this->assertNull($o);
        $this->assertNull($k);
    }

    /**
     * @test
     */
    public function shouldTripleCoalesceToNull()
    {
        // when
        [$a, $b, $c] = pattern('(a)?(b)?(c)?')->match('')->triple(1, 2, 3);

        // then
        $this->assertNull($a);
        $this->assertNull($b);
        $this->assertNull($c);
    }

    /**
     * @test
     */
    public function shouldReturnTuple_filter()
    {
        // when
        [$value, $unit] = pattern('(\d+)(?<unit>cm|mm)')
            ->match('12cm 14mm')
            ->filter(function (Detail $match) {
                return $match->text() === '14mm';
            })
            ->tuple(1, 'unit');

        // then
        $this->assertEquals(14, $value);
        $this->assertEquals('mm', $unit);
    }

    /**
     * @test
     */
    public function shouldReturnTriple_filter()
    {
        // when
        [$a, $b, $c] = pattern('([ab])([12])([$%])')->match('a1% b2$')
            ->filter(function (Detail $match) {
                return $match->text() !== 'a1%';
            })
            ->triple(1, 3, 2);

        // then
        $this->assertEquals('b', $a);
        $this->assertEquals('$', $b);
        $this->assertEquals('2', $c);
    }

    /**
     * @test
     */
    public function shouldThrow_tuple_onUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get a tuple of groups '0' and '0' from the first match, but subject was not matched at all");

        // then
        pattern('Foo')->match('Bar')->tuple(0, 0);
    }

    /**
     * @test
     */
    public function shouldThrow_triple_onUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get a triple of groups '0', '0' and '0' from the first match, but subject was not matched at all");

        // then
        pattern('Foo')->match('Bar')->triple(0, 0, 0);
    }

    /**
     * @test
     * @dataProvider tupleGroups
     * @param int $group1
     * @param int $group2
     */
    public function shouldThrow_tuple_onMissingGroup($group1, $group2)
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // then
        pattern('Foo')->match('Bar')->tuple($group1, $group2);
    }

    public function tupleGroups(): array
    {
        return [
            ['missing', 0],
            [0, 'missing'],
        ];
    }

    /**
     * @test
     * @dataProvider tripleGroups
     * @param int $group1
     * @param int $group2
     * @param int $group3
     */
    public function shouldThrow_triple_onMissingGroup($group1, $group2, $group3)
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // then
        pattern('Foo')->match('Bar')->triple($group1, $group2, $group3);
    }

    public function tripleGroups(): array
    {
        return [
            ['missing', 0, 0],
            [0, 'missing', 0],
            [0, 0, 'missing'],
        ];
    }
}
