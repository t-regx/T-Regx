<?php
namespace Test\Unit\CleanRegex\Internal\Model;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Model\ConstantGroupAware;
use Test\Fakes\CleanRegex\Internal\Model\ThrowFalseNegative;
use Test\Fakes\CleanRegex\Internal\Pcre\Legacy\ConstantAll;
use Test\Fakes\CleanRegex\Internal\Pcre\Legacy\ThrowFactory;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Pcre\Legacy\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchOffset;

/**
 * @covers \TRegx\CleanRegex\Internal\Pcre\Legacy\GroupPolyfillDecorator
 */
class GroupPolyfillDecoratorTest extends TestCase
{
    /**
     * @test
     */
    public function test_hasGroup_true()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->match(['group' => 'value']), new ThrowFactory(), -10);

        // when, then
        $this->assertTrue($decorator->hasGroup(new GroupName('group')));
    }

    /**
     * @test
     */
    public function test_hasGroup_call()
    {
        // given
        $decorator = new GroupPolyfillDecorator(new ThrowFalseNegative(), new ConstantAll(['group' => null]), -10);

        // when, then
        $this->assertTrue($decorator->hasGroup(new GroupName('group')));
    }

    /**
     * @test
     */
    public function test_hasGroup_false()
    {
        // given
        $decorator = new GroupPolyfillDecorator(new ThrowFalseNegative(), new ConstantAll([]), -10);

        // when, then
        $this->assertFalse($decorator->hasGroup(new GroupName('group')));
    }

    /**
     * @test
     */
    public function test_isGroupMatched_true()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->match(['group' => ['value', 0]]), new ThrowFactory(), -10);

        // when, then
        $this->assertTrue($decorator->isGroupMatched('group'));
    }

    /**
     * @test
     */
    public function test_isGroupMatched_call()
    {
        // given
        $decorator = new GroupPolyfillDecorator(new ThrowFalseNegative(), new ThrowFactory(), 0);
        // when, then
        $this->assertFalse($decorator->isGroupMatched('group'));
    }

    /**
     * @test
     */
    public function test_isGroupMatched_call_explicitlyNotMatched()
    {
        // This is the current assumption, that GroupPolyfillDecorator should
        // not try to re-fetch groups that are explicitly not matched (offset -1)
        // but it's not certain whether this assumption will always be true.

        // given
        $decorator = new GroupPolyfillDecorator($this->match(['group' => ['value', -1]]), new ThrowFactory(), -10);

        // when, then
        $this->assertFalse($decorator->isGroupMatched('group'));
    }

    /**
     * @test
     */
    public function test_isGroupMatched_false()
    {
        // given
        $decorator = new GroupPolyfillDecorator(new ThrowFalseNegative(), new ConstantAll(['group' => [[null, -1]]]), 0);

        // when, then
        $this->assertFalse($decorator->isGroupMatched('group'));
    }

    /**
     * @test
     */
    public function test_getGroupTextAndOffset()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->match(['group' => ['Foo', 2]]), new ThrowFactory(), -10);

        // when, then
        $this->assertSame(['Foo', 2], $decorator->getGroupTextAndOffset('group'));
    }

    /**
     * @test
     */
    public function test_getGroupTextAndOffset_call()
    {
        // given
        $decorator = new GroupPolyfillDecorator(new ThrowFalseNegative(), new ConstantAll(['group' => [['Foo', 2]]]), 0);

        // when, then
        $this->assertSame(['Foo', 2], $decorator->getGroupTextAndOffset('group'));
    }

    /**
     * @test
     */
    public function test_getGroupTextAndOffset_call_explicitlyNotMatched()
    {
        // This is the current assumption, that GroupPolyfillDecorator should
        // not try to re-fetch groups that are explicitly not matched (offset -1)
        // but it's not certain whether this assumption will always be true.

        // given
        $decorator = new GroupPolyfillDecorator($this->match(['group' => ['value', -1]]), new ThrowFactory(), -10);

        // when, then
        $this->assertSame(['value', -1], $decorator->getGroupTextAndOffset('group'));
    }

    /**
     * @test
     */
    public function test_getText()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->match([0 => ['value', -10]]), new ThrowFactory(), -10);

        // when, then
        $this->assertSame('value', $decorator->text());
    }

    /**
     * @test
     */
    public function test_getByteOffset()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->match([0 => ['value', 15]]), new ThrowFactory(), -10);

        // when, then
        $this->assertSame(15, $decorator->byteOffset());
    }

    /**
     * @test
     */
    public function test_getGroupKeys()
    {
        // given
        $decorator = new GroupPolyfillDecorator(new ThrowFalseNegative(), new ThrowFactory(), 0, new ConstantGroupAware(['one', 'two']));
        // when, then
        $this->assertSame(['one', 'two'], $decorator->getGroupKeys());
    }

    /**
     * @test
     */
    public function test_hasGroup()
    {
        // given
        $decorator = new GroupPolyfillDecorator(new ThrowFalseNegative(), new ThrowFactory(), 0, new ConstantGroupAware(['one', 'two']));
        // when, then
        $this->assertTrue($decorator->hasGroup(GroupKey::of('one')));
        $this->assertTrue($decorator->hasGroup(GroupKey::of('two')));
        $this->assertFalse($decorator->hasGroup(GroupKey::of('three')));
    }

    private function match(array $match): FalseNegative
    {
        return new FalseNegative(new RawMatchOffset($match));
    }
}
