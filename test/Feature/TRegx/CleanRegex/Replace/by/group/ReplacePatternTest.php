<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider optionals
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
        $this->assertEquals('Replace O!, T! and O!', $result);
    }

    function optionals(): array
    {
        return [
            'orReturn' => ['orReturn', ['word']],
            'orElse'   => ['orElse', [function () {
            }]],
            'orThrow'  => ['orThrow', []],
        ];
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
            ->replace('FooCar')
            ->all()
            ->by()
            ->group('bar')
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
            $group->orThrow();
        } catch (GroupNotMatchedException $ignored) {
        }

        // when
        $group->orThrow();
    }

    /**
     * @test
     */
    public function shouldReplace_indexed()
    {
        // when
        $result = pattern('https?://(\w+)\.com')
            ->replace('Links: https://google.com and http://facebook.com')
            ->all()
            ->by()
            ->group(1)
            ->orThrow();

        // then
        $this->assertEquals('Links: google and facebook', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_named()
    {
        // when
        $result = pattern('https?://(?<domain>\w+)\.com')
            ->replace('Links: https://google.com and http://facebook.com')
            ->all()
            ->by()
            ->group('domain')
            ->orThrow();

        // then
        $this->assertEquals('Links: google and facebook', $result);
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
            ->orThrow();
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
            ->orReturn('failing');
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidGroup()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string starting with a letter, given: '2group'");

        // when
        pattern('')
            ->replace('')
            ->all()
            ->by()
            ->group('2group')
            ->orReturn('');
    }

    /**
     * @test
     * @dataProvider nonexistentGroups
     * @param $group
     */
    public function shouldThrow_forNonExistentGroup($group)
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: '$group'");

        // when
        pattern('https?://(\w+)\.com')
            ->replace('Links: https://google.com and http://facebook.com')
            ->all()
            ->by()
            ->group($group)
            ->orReturn('failing');
    }

    function nonexistentGroups(): array
    {
        return [
            ['missing'],
            [40],
        ];
    }

    /**
     * @test
     */
    public function shouldThrow_custom_notMatchedGroup()
    {
        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage("Expected to replace with group '1', but the group was not matched");

        // when
        pattern('(https?)?://(\w+)\.com')
            ->replace('Links: https://google.com and ://facebook.com')
            ->all()
            ->by()
            ->group(1)
            ->orThrow(CustomException::class);
    }
}
