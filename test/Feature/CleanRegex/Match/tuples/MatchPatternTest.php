<?php
namespace Test\Feature\TRegx\CleanRegex\Match\tuples;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;

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
        $this->assertSame('12', $value);
        $this->assertSame('cm', $unit);
    }

    /**
     * @test
     */
    public function shouldReturnTriple()
    {
        // when
        [$a, $b, $c] = pattern('([ab])([12])([$%])')->match('a1% b2$')->triple(1, 3, 2);

        // then
        $this->assertSame('a', $a);
        $this->assertSame('%', $b);
        $this->assertSame('1', $c);
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
    public function shouldThrow_tuple_onUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get a tuple of groups #2 and #3 from the first match, but subject was not matched at all");

        // then
        pattern('(F)(o)(o)')->match('Bar')->tuple(2, 3);
    }

    /**
     * @test
     */
    public function shouldThrow_triple_onUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get a triple of groups #0, #1 and #2 from the first match, but subject was not matched at all");

        // then
        pattern('(F)(o)(o)')->match('Bar')->triple(0, 1, 2);
    }

    /**
     * @test
     * @dataProvider tupleGroups
     * @param int|string $group1
     * @param int|string $group2
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
     * @param int|string $group1
     * @param int|string $group2
     * @param int|string $group3
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
