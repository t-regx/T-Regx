<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Exception\UnmatchedStreamException;
use TRegx\CleanRegex\Match\Details\Group\Group;

/**
 * @coversNothing
 */
class MatchPatternTest extends TestCase
{
    use AssertsSameMatches;

    /**
     * @test
     */
    public function shouldGet_all()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->all();

        // then
        $this->assertSame(['omputer', null, 'hree', 'our'], $groups);
    }

    /**
     * @test
     */
    public function shouldGet_all_unmatched()
    {
        // given
        $subject = 'NOT MATCHING';

        // when
        $all = pattern('[A-Z](?<lowercase>[a-z]+)')->match($subject)->group('lowercase')->all();

        // then
        $this->assertEmpty($all);
    }

    /**
     * @test
     */
    public function shouldGet_onlyOne_unmatched()
    {
        // given
        $subject = 'NOT MATCHING';

        // when
        $all = pattern('[A-Z](?<lowercase>[a-z]+)')->match($subject)->group('lowercase')->only(1);

        // then
        $this->assertEmpty($all);
    }

    /**
     * @test
     */
    public function shouldThrow_all_nonexistent()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->all();
    }

    /**
     * @test
     */
    public function shouldThrow_first_nonexistent()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_consumer_nonexistent()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->findFirst(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldThrow_onlyOne_nonexistent()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->only(1);
    }

    /**
     * @test
     */
    public function shouldGet_only()
    {
        // when
        $groups1 = pattern('[A-Z](?<lowercase>[a-z]+)?')->match('D Computer')->group('lowercase')->only(1);
        $groups2 = pattern('[A-Z](?<lowercase>[a-z]+)?')->match('D Computer')->group('lowercase')->only(2);

        // then
        $this->assertSame([null], $groups1);
        $this->assertSame([null, 'omputer'], $groups2);
    }

    /**
     * @test
     */
    public function shouldMap()
    {
        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('D Computer')
            ->group('lowercase')
            ->map(function (Group $group) {
                if ($group->matched()) {
                    return $group->text();
                }
                return "unmatched";
            });

        // then
        $this->assertSame(['unmatched', 'omputer'], $groups);
    }

    /**
     * @test
     */
    public function shouldForEach()
    {
        // given
        $groups = [];

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('D Computer')
            ->group('lowercase')
            ->forEach(function (Group $group) use (&$groups) {
                $groups[] = $group->orReturn("unmatched");
            });

        // then
        $this->assertSame(['unmatched', 'omputer'], $groups);
    }

    /**
     * @test
     */
    public function shouldFilter()
    {
        // when
        $groups = pattern('\d+(?<unit>kg|[cm]?m)')
            ->match('15mm 12kg 16m 17cm 27kg')
            ->group('unit')
            ->filter(function (Group $group) {
                return $group->text() !== "kg";
            });

        // then
        $this->assertSame(['mm', 'm', 'cm'], $groups);
    }

    /**
     * @test
     */
    public function shouldThrow_filter_forInvalidReturnType()
    {
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but null given');

        // when
        pattern('\d+(?<unit>kg|[cm]?m)')
            ->match('15mm 12kg 16m 17cm 27kg')
            ->group('unit')
            ->filter(Functions::constant(null));
    }

    /**
     * @test
     */
    public function shouldGet_offsets()
    {
        // given
        $offsets = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd Computer L Three Four')
            ->group('lowercase')
            ->offsets();

        // when
        $only1 = $offsets->only(1);
        $only2 = $offsets->only(2);
        $all = $offsets->all();

        // then
        $this->assertSame([4], $only1);
        $this->assertSame([4, null], $only2);
        $this->assertSame([4, null, 15, 21], $all);
    }

    /**
     * @test
     */
    public function shouldGet_offsets_onlyOne_null()
    {
        // given
        $offsets = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd L Three Four')
            ->group('lowercase')
            ->offsets();

        // when
        $only1 = $offsets->only(1);

        // then
        $this->assertSame([null], $only1);
    }

    /**
     * @test
     */
    public function shouldGet_nth()
    {
        // given
        $subject = '12cm 14mm 13cm 19cm';

        // when
        $result = pattern('(?<value>\d+)(?<unit>cm|mm)')->match($subject)->group('value')->nth(3);

        // then
        $this->assertSame('19', $result);
    }

    /**
     * @test
     */
    public function shouldBeIterable()
    {
        // given
        $iterable = pattern('\d+([cm]?m)')->match('14cm 12mm 18m')->group(1);

        // when
        $result = iterator_to_array($iterable);

        // then
        $this->assertSameMatches(['cm', 'mm', 'm'], $result);
    }

    /**
     * @test
     */
    public function shouldBeIterable_OnUnmatchedSubject()
    {
        // when
        foreach (pattern('(Foo)')->match('Bar')->group(1) as $_) {
            $this->fail();
        }

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     * @dataProvider streamMethods
     * @param string $method
     */
    public function shouldPass_method_OnUnmatchedSubject(string $method)
    {
        // when
        pattern('(Foo)')->match('Bar')->group(1)->$method(Functions::fail());

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldThrow_group_forEach_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #1');

        // when
        pattern('Foo')->match('Bar')->group(1)->forEach(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldThrow_group_first_OnUnmatchedSubject_OnNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #1');

        // when
        pattern('Foo')->match('Bar')->group(1)->first(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldPassThrough_first()
    {
        // then
        $this->expectException(UnmatchedStreamException::class);

        // when
        pattern('(Foo)')->match('Foo')->group(1)->first(Functions::throws(new UnmatchedStreamException()));
    }

    /**
     * @test
     * @dataProvider streamMethods
     * @param string $method
     */
    public function shouldPassThrough_method(string $method)
    {
        // then
        $this->expectException(UnmatchedStreamException::class);

        // when
        pattern('(Foo)')->match('Foo')->group(1)->$method(Functions::throws(new UnmatchedStreamException()));
    }

    public function streamMethods(): array
    {
        return [
            ['map'],
            ['flatMap'],
            ['flatMapAssoc'],
            ['forEach'],
            ['filter'],
        ];
    }
}
