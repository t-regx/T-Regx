<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group\stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Details\NotMatched;

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
        pattern('Foo')->match('Foo')->group('missing')->stream()->all();
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
            ->stream()
            ->filter(DetailFunctions::notEquals("kg"))
            ->all();

        // then
        $this->assertSameMatches(['mm', 2 => 'm', 3 => 'cm'], $groups);
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
            ->stream()
            ->map(function (Group $group) {
                return $group->or("unmatched");
            })
            ->all();

        // then
        $this->assertSame(['unmatched', 'omputer'], $groups);
    }

    /**
     * @test
     * @dataProvider streamMethods
     * @param string $method
     */
    public function shouldThrow_method_OnUnmatchedSubject_OnNonexistentGroup(string $method)
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #1');

        // when
        pattern('Foo')->match('Bar')->group(1)->stream()->$method();
    }

    public function streamMethods(): array
    {
        return [['first'], ['all']];
    }

    /**
     * @test
     * @dataProvider streamMethods
     * @param string $method
     */
    public function shouldThrow_keys_method_OnUnmatchedSubject_OnNonexistentGroup(string $method)
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #1');

        // when
        pattern('Foo')->match('Bar')->group(1)->stream()->keys()->$method();
    }

    /**
     * @test
     */
    public function shouldThrow_group_stream_keys_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get group #0 from the first match, but subject was not matched at all');

        // when
        pattern('Foo')->match('Bar')->group(0)->stream()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldGet_keys_all_OnUnmatchedSubject()
    {
        // when
        $all = pattern('Foo')->match('Bar')->group(0)->stream()->keys()->all();

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
            ->stream()
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
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get group #0 from the first match, but subject was not matched at all');

        // when
        pattern('Foo')->match('Bar')->group(0)->stream()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_()
    {
        // when
        $group = pattern('(Foo)(Bar)?')->match('Foo')->group(2)->stream()->first();

        // then
        $this->assertFalse($group->matched());
    }

    /**
     * @test
     */
    public function shouldGetSecondEmptyGroup()
    {
        // when
        $group = pattern('(Foo)()')->match('Foo')->group(2)->stream()->first();

        // then
        $this->assertTrue($group->matched());
    }

    /**
     * @test
     */
    public function shouldThrow_keys_first_()
    {
        // when
        $key = pattern('(Foo)(Bar)?')->match('Foo')->group(2)->stream()->keys()->first();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldHaveGroup_lastGroup()
    {
        // when
        pattern('(?<one>Foo)(?<last>Bar)?')
            ->match('Foo')
            ->group('last')
            ->stream()
            ->first()
            ->map(Functions::identity())
            ->orElse(function (NotMatched $notMatched) {
                $this->assertSame(['one', 'last'], $notMatched->groupNames());
                $this->assertTrue($notMatched->hasGroup('last'));
                $this->assertTrue($notMatched->hasGroup('one'));
                $this->assertFalse($notMatched->hasGroup('two'));
            });
    }
}
