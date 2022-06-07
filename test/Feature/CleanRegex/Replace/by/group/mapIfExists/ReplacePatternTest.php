<?php
namespace Test\Feature\CleanRegex\Replace\by\group\mapIfExists;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace()
    {
        // when
        $result = pattern('(?<capital>[OT])(ne|wo)')
            ->replace('Replace One!, Two! and One!')
            ->all()
            ->by()
            ->group('capital')
            ->mapIfExists([
                'O' => '1',
                'T' => '2'
            ])
            ->orElseThrow();

        // then
        $this->assertSame('Replace 1!, 2! and 1!', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidGroupName()
    {
        // given
        $groupName = '2group';

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");

        // when
        pattern('(?<capital>[OT])(ne|wo)')
            ->replace('')
            ->all()
            ->by()
            ->group($groupName)
            ->mapIfExists([])
            ->orElseCalling(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldThrow_onNonExistingGroup()
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
            ->mapIfExists([])
            ->orElseWith('failing');
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
            ->mapIfExists(['' => 'failure'])
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
            ->replace('FooBarCar FooBarCar FooCar')
            ->all()
            ->by()
            ->group('bar')
            ->mapIfExists([
                'Bar' => 'ok',
                ''    => 'failure'
            ])
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
            $group
                ->mapIfExists([
                    'Bar' => 'ok',
                    ''    => 'failure'
                ])
                ->orElseThrow();
        } catch (GroupNotMatchedException $ignored) {
        }

        // when
        $group
            ->mapIfExists([
                'Bar' => 'ok',
                ''    => 'failure'
            ])
            ->orElseThrow();
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
            ->mapIfExists([])
            ->orElseThrow();
    }

    /**
     * @test
     */
    public function shouldNotReplace_onMissingReplacementsKey()
    {
        // given
        $subject = 'Replace One and Two, and maybe Four';
        $map = [
            'O' => '1',
            'T' => '2'
        ];

        // when
        $result = pattern('(?<capital>[OTF])(ne|wo|our)')
            ->replace($subject)
            ->all()
            ->by()
            ->group('capital')
            ->mapIfExists($map)
            ->orElseThrow();

        // then
        $this->assertSame('Replace 1 and 2, and maybe Four', $result);
    }

    /**
     * @test
     */
    public function shouldReplaceWithNumericString()
    {
        // given
        $map = ['123' => '345'];

        // when
        $result = pattern('(123)')->replace('123')->first()->by()->group(1)->mapIfExists($map)->orElseWith('failing');

        // then
        $this->assertSame('345', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidValue()
    {
        // given
        $map = ['' => true];

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid replacement map value. Expected string, but boolean (true) given");

        // when
        pattern('(One|Two)')->replace('')->first()->by()->group(1)->mapIfExists($map)->orElseWith('failing');
    }
}
