<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupIndex;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;
use TRegx\CleanRegex\Match\Details\LazyDetail;
use TRegx\DataProvider\DataProviders;

/**
 * @coversNothing
 */
class ReplacePatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider optionals
     * @param string $method
     * @param array $arguments
     */
    public function shouldReplace(string $method, array $arguments)
    {
        // given
        $subject = 'Replace One!, Two! and One!';

        // when
        $result = pattern('(?<capital>[OT])(ne|wo)')
            ->replace($subject)
            ->all()
            ->by()
            ->group('capital')
            ->$method(...$arguments);

        // then
        $this->assertSame('Replace O!, T! and O!', $result);
    }

    public function optionals(): array
    {
        return [
            'orElseThrow'   => ['orElseThrow', []],
            'orElseIgnore'  => ['orElseIgnore', []],
            'orElseEmpty'   => ['orElseEmpty', []],
            'orElseWith'    => ['orElseWith', ['word']],
            'orElseCalling' => ['orElseCalling', [Functions::identity()]],
        ];
    }

    /**
     * @test
     */
    public function shouldNotReplace_passMatchDetails_orElse()
    {
        // when
        pattern('(?<value>\d+)(?<unit>cm)?')
            ->replace('15cm 14 16cm')
            ->all()
            ->by()
            ->group('unit')
            ->orElseCalling(function (LazyDetail $detail) {
                $this->assertSame('14', $detail->text());
                $this->assertSame('14', $detail->get('value'));
                $this->assertSame('14', $detail->group('value')->text());
                $this->assertSame(['cm', null, 'cm'], $detail->group('unit')->all());
                // Not, really testable
                $this->assertSame(1, $detail->index());
                $this->assertSame(-1, $detail->limit());
                $this->assertSame('15cm 14 16cm', $detail->subject());

                // clean up
                return 'else';
            });
    }

    /**
     * @test
     */
    public function shouldThrow_orElse_returnNull()
    {
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid orElseCalling() callback return type. Expected string, but null given');

        // when
        pattern('(?<value>\d+)(?<unit>cm)?')
            ->replace('15cm 14 16cm')
            ->all()
            ->by()
            ->group('unit')
            ->orElseCalling(function (LazyDetail $detail) {
                $this->assertSame('14', $detail->text());

                // when
                return null;
            });
    }

    /**
     * @test
     */
    public function shouldThrow_orElse_returnArray()
    {
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid orElseCalling() callback return type. Expected string, but null given');

        // when
        pattern('(?<value>\d+)(?<unit>cm)?')
            ->replace('15cm 14 16cm')
            ->all()
            ->by()
            ->group('unit')
            ->orElseCalling(function (LazyDetail $detail) {
                $this->assertSame('14', $detail->text());

                // when
                return [2];
            });
    }

    /**
     * @test
     * @dataProvider shouldNotReplaceGroups
     * @param string|int $group
     * @param string $method
     * @param array $arguments
     * @param string $expected
     */
    public function shouldNotReplace($group, string $method, array $arguments, string $expected)
    {
        // when
        $result = pattern('https?://(?<name>NotMatched)?\.com')
            ->replace('Links: https://.com,http://.com.')
            ->all()
            ->by()
            ->group($group)
            ->$method(...$arguments);

        // then
        $this->assertSame($expected, $result);
    }

    public function shouldNotReplaceGroups(): array
    {
        return DataProviders::builder()
            ->addSection('name', 1)
            ->addJoinedSection(
                ['orElseIgnore', [], 'Links: https://.com,http://.com.'],
                ['orElseEmpty', [], 'Links: ,.'],
                ['orElseWith', ['default'], 'Links: default,default.'],
                ['orElseCalling', [Functions::constant('else')], 'Links: else,else.']
            )
            ->build();
    }

    /**
     * @test
     * @dataProvider groups
     * @param string|int $groupIdentifier
     * @param string $group
     */
    public function shouldNotReplace_orThrow($groupIdentifier, string $group)
    {
        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage("Expected to replace with group $group, but the group was not matched");

        // when
        pattern('https?://(?<name>NotMatched)?\.com')
            ->replace('Links: https://.com,http://.com.')
            ->all()
            ->by()
            ->group($groupIdentifier)
            ->orElseThrow(CustomSubjectException::class);
    }

    public function groups(): array
    {
        return [['name', "'name'"], [1, '#1']];
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch_middleGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'bar', but the group was not matched");

        // when
        pattern('Foo(?<bar>Bar)?(?<car>Car)')
            ->replace('FooCar')
            ->all()
            ->by()
            ->group('bar')
            ->orElseThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch_middleGroup_thirdIndex()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'bar', but the group was not matched");

        // when
        pattern('Foo(?<bar>Bar)?(?<car>Car)')
            ->replace('FooCar')
            ->all()
            ->by()
            ->group('bar')
            ->orElseThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch_lastGroup_thirdIndex_breaking()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'bar', but the group was not matched");

        $group = pattern('(?<foo>Foo)(?<bar>Bar)?(?<last>;)')
            ->replace('FooBar; FooBar; Foo; Foo;')
            ->all()
            ->by()
            ->group('bar');

        try {
            $group->orElseThrow();
        } catch (GroupNotMatchedException $ignored) {
        }

        // when
        $group->orElseThrow();
    }

    /**
     * @test
     */
    public function shouldReplace_indexed()
    {
        // when
        $result = pattern('https?://(google|facebook)\.com')
            ->replace('Links: https://google.com and http://facebook.com')
            ->all()
            ->by()
            ->group(1)
            ->orElseThrow();

        // then
        $this->assertSame('Links: google and facebook', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_wholeMatch()
    {
        // given
        $subject = 'Links: https://google.com and http://facebook.com';

        // when
        $result = pattern('https?://(google|facebook)\.com')->replace($subject)->all()->by()->group(0)->orElseThrow();

        // then
        $this->assertSame($subject, $result);
    }

    /**
     * @test
     */
    public function shouldReplace_named()
    {
        // when
        $result = pattern('https?://(?<domain>google|facebook)\.com')
            ->replace('Links: https://google.com and http://facebook.com')
            ->all()
            ->by()
            ->group('domain')
            ->orElseThrow();

        // then
        $this->assertSame('Links: google and facebook', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch_lastGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'bar', but the group was not matched");

        // when
        pattern('Foo(?<bar>Bar)?')
            ->replace('Foo')
            ->all()
            ->by()
            ->group('bar')
            ->orElseThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_forNonExistingGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('(?<capital>foo)')
            ->replace('foo')
            ->all()
            ->by()
            ->group('missing')
            ->orElseWith('failing');
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidGroup()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");

        // when
        pattern('')->replace('')->all()->by()->group('2group')->orElseWith('');
    }

    /**
     * @test
     * @dataProvider nonexistentGroups
     * @param GroupKey $group
     */
    public function shouldThrow_forNonExistentGroup(GroupKey $group)
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: $group");

        // when
        pattern('https?://(google|facebook)\.com')
            ->replace('Links: https://google.com and http://facebook.com')
            ->all()
            ->by()
            ->group($group->nameOrIndex())
            ->orElseWith('failing');
    }

    public function nonexistentGroups(): array
    {
        return [
            [new GroupName('missing')],
            [new GroupIndex(40)],
        ];
    }

    /**
     * @test
     */
    public function shouldThrow_custom_notMatchedGroup()
    {
        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage("Expected to replace with group #1, but the group was not matched");

        // when
        pattern('(https?)?://(google|facebook)\.com')
            ->replace('Links: https://google.com and ://facebook.com')
            ->all()
            ->by()
            ->group(1)
            ->orElseThrow(CustomSubjectException::class);
    }
}
