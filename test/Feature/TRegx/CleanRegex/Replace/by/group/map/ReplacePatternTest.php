<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group\map;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\MissingReplacementKeyException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Match;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     * @happyPath
     * @dataProvider optionals
     */
    public function shouldReplace(string $method, array $arguments)
    {
        // given
        $subject = 'Replace One!, Two! and One!';
        $map = [
            'O' => '1',
            'T' => '2'
        ];

        // when
        $result = pattern('(?<capital>[OT])(ne|wo)')
            ->replace($subject)
            ->all()
            ->by()
            ->group('capital')
            ->map($map)
            ->$method(...$arguments);

        // then
        $this->assertEquals('Replace 1!, 2! and 1!', $result);
    }

    function optionals(): array
    {
        return [
            'orReturn' => ['orReturn', ['word']],
            'orElse'   => ['orElse', [function (Match $match) {
            }]],
            'orThrow'  => ['orThrow', []],
        ];
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
        $this->expectExceptionMessage("Group name must be an alphanumeric string starting with a letter, given: '2group'");

        // when
        pattern('(?<capital>[OT])(ne|wo)')
            ->replace('')
            ->all()
            ->by()
            ->group($groupName)
            ->map([])
            ->orReturn('failing');
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
            ->map([])
            ->orReturn('failing');
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
            ->map(['' => 'failure'])
            ->orThrow();
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
            ->map([
                'Bar' => 'ok',
                ''    => 'failure'
            ])
            ->orThrow();
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
                ->map([
                    'Bar' => 'ok',
                    ''    => 'failure'
                ])
                ->orThrow();
        } catch (GroupNotMatchedException $ignored) {
        }

        // when
        $group
            ->map([
                'Bar' => 'ok',
                ''    => 'failure'
            ])
            ->orThrow();
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
            ->map([])
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldThrow_onMissingReplacementsKey()
    {
        // given
        $subject = 'Replace One and Two, and maybe Four';
        $map = [
            'O' => '1',
            'T' => '2'
        ];

        // then
        $this->expectException(MissingReplacementKeyException::class);
        $this->expectExceptionMessage("Expected to replace value 'Four' by group 'capital' ('F'), but such key is not found in replacement map.");

        // when
        pattern('(?<capital>[OTF])(ne|wo|our)')
            ->replace($subject)
            ->all()
            ->by()
            ->group('capital')
            ->map($map)
            ->orReturn('failing');
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
            ->orThrow();

        // then
        $this->assertEquals('Replace 1 and 2, and maybe Four', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidKey()
    {
        // given
        $map = [2 => ''];

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid replacement map key. Expected string, but integer (2) given");

        // when
        pattern('(One|Two)')->replace('')->first()->by()->group(1)->map($map)->orReturn('failing');
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
        pattern('(One|Two)')->replace('')->first()->by()->group(1)->map($map)->orReturn('failing');
    }
}
