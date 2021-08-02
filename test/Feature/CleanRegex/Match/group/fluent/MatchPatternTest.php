<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\fluent;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Details\NotMatched;

/**
 * @coversNothing
 */
class MatchPatternTest extends TestCase
{
    use AssertsSameMatches;

    /**
     * @test
     */
    public function shouldThrow_all_OnNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('Foo')->match('Foo')->group('missing')->fluent()->all();
    }

    /**
     * @test
     */
    public function shouldGet_filter_all()
    {
        // when
        $groups = pattern('\d+(?<unit>kg|[cm]?m)')
            ->match('15mm 12kg 16m 17cm 27kg')
            ->group('unit')
            ->fluent()
            ->filter(Functions::notEquals("kg"))
            ->all();

        // then
        $this->assertSameMatches(['mm', 2 => 'm', 3 => 'cm'], $groups);
    }

    /**
     * @test
     */
    public function shouldReturn_keys_all()
    {
        // when
        $groups = pattern('\d+(?<unit>kg|[cm]?m)')
            ->match('15mm 12kg 16m 17cm 27kg')
            ->remaining(Functions::equals('16m'))
            ->group('unit')
            ->fluent()
            ->keys()
            ->all();

        // then
        $this->assertSame([2], $groups);
    }

    /**
     * @test
     */
    public function shouldReturn_keys_first()
    {
        // when
        $groups = pattern('\d+(?<unit>kg|[cm]?m)')
            ->match('15mm 12kg 16m 17cm 27kg')
            ->remaining(Functions::equals('16m'))
            ->group('unit')
            ->fluent()
            ->keys()
            ->first();

        // then
        $this->assertSame(2, $groups);
    }

    /**
     * @test
     */
    public function shouldGet_map()
    {
        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('D Computer')
            ->group('lowercase')
            ->fluent()
            ->map(function (Group $group) {
                return $group->orReturn("unmatched");
            })
            ->all();

        // then
        $this->assertSame(['unmatched', 'omputer'], $groups);
    }

    /**
     * @test
     * @dataProvider streamFluentMethods
     * @param string $method
     */
    public function shouldThrow_method_OnUnmatchedSubject_OnNonexistentGroup(string $method)
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #1');

        // when
        pattern('Foo')->match('Bar')->group(1)->fluent()->$method();
    }

    public function streamFluentMethods(): array
    {
        return [['first'], ['all']];
    }

    /**
     * @test
     * @dataProvider streamFluentMethods
     * @param string $method
     */
    public function shouldThrow_keys_method_OnUnmatchedSubject_OnNonexistentGroup(string $method)
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #1');

        // when
        pattern('Foo')->match('Bar')->group(1)->fluent()->keys()->$method();
    }

    /**
     * @test
     */
    public function shouldThrow_group_fluent_keys_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the first element from fluent pattern, but the subject backing the feed was not matched');

        // when
        pattern('Foo')->match('Bar')->group(0)->fluent()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldGet_keys_all_OnUnmatchedSubject()
    {
        // when
        $all = pattern('Foo')->match('Bar')->group(0)->fluent()->keys()->all();

        // then
        $this->assertSame([], $all);
    }

    /**
     * @test
     */
    public function shouldGet_map_all(): void
    {
        // when
        pattern('(Foo|Bar)')
            ->match(' Foo Bar')
            ->group(1)
            ->fluent()
            ->map(function (Group $group) {
                $this->assertSame($group->offset() === 1 ? 'Foo' : 'Bar', $group->text());
            })
            ->all();
    }

    /**
     * @test
     */
    public function shouldThrow_first(): void
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the first element from fluent pattern, but the subject backing the feed was not matched');

        // when
        pattern('Foo')->match('Bar')->group(0)->fluent()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_callback(): void
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the first element from fluent pattern, but the subject backing the feed was not matched');

        // when
        pattern('Foo')->match('Bar')->group(0)->fluent()->first(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldThrow_first_()
    {
        // when
        $group = pattern('(Foo)(Bar)?')->match('Foo')->group(2)->fluent()->first();

        // then
        $this->assertFalse($group->matched());
    }

    /**
     * @test
     */
    public function shouldThrow_keys_first_()
    {
        // when
        $key = pattern('(Foo)(Bar)?')->match('Foo')->group(2)->fluent()->keys()->first();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldHaveGroup_lastGroup()
    {
        // when
        /** @var Group $group */
        $group = pattern('(?<one>Foo)(?<last>Bar)?')
            ->match('Foo')
            ->group('last')
            ->fluent()
            ->first();

        // then
        $group->orElse(function (NotMatched $notMatched) {
            $this->assertSame(['one', 'last'], $notMatched->groupNames());
            $this->assertTrue($notMatched->hasGroup('last'));
        });
    }
}
