<?php
namespace Test\Unit\CleanRegex\Internal\Model;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Model\ConstantGroupAware;
use Test\Fakes\CleanRegex\Internal\Model\ThrowFalseNegative;
use Test\Fakes\CleanRegex\Internal\Model\ThrowGroupAware;
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
    public function test_getGroupsTexts()
    {
        // given
        $matches = [
            'foo'   => [['bar', -10]],
            'lorem' => [['ipsum', -10]],
        ];
        $decorator = new GroupPolyfillDecorator($this->match([0 => ['one', 15]]), new ConstantAll($matches), 0);

        // when, then
        $this->assertSame(['foo' => 'bar', 'lorem' => 'ipsum'], $decorator->groupTexts());
    }

    /**
     * @test
     */
    public function test_getGroupsOffsets()
    {
        // given
        $matches = [
            'foo'   => [['bar', 10]],
            'lorem' => [['ipsum', 15]],
        ];
        $decorator = new GroupPolyfillDecorator($this->match([0 => ['one', 0]]), new ConstantAll($matches), 0);

        // when, then
        $this->assertSame(['foo' => 10, 'lorem' => 15], $decorator->groupOffsets());
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

    /**
     * @test
     */
    public function test_getGroupKeys_second()
    {
        // given
        $matches = [
            'foo'   => [['bar', 10]],
            'lorem' => [['ipsum', 15]],
        ];
        $decorator = new GroupPolyfillDecorator($this->match([0 => ['one', 0]]), new ConstantAll($matches), 0, new ThrowGroupAware());
        $decorator->groupTexts();
        // when, then
        $this->assertSame(['foo', 'lorem'], $decorator->getGroupKeys());
    }

    /**
     * @test
     */
    public function test_getGroup_true()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->match(['group' => ['value', 0]]), new ThrowFactory(), -10);

        // when, then
        $this->assertSame('value', $decorator->getGroup('group'));
    }

    /**
     * @test
     */
    public function test_getGroup_call()
    {
        // given
        $decorator = new GroupPolyfillDecorator(new ThrowFalseNegative(), new ConstantAll(['group' => [['value', 0]]]), 0);

        // when, then
        $this->assertSame('value', $decorator->getGroup('group'));
    }

    /**
     * @test
     */
    public function test_getGroup_call_explicitlyNotMatched()
    {
        // This is the current assumption, that GroupPolyfillDecorator should
        // not try to re-fetch groups that are explicitly not matched (offset -1)
        // but it's not certain whether this assumption will always be true.

        // given
        $decorator = new GroupPolyfillDecorator($this->match(['group' => ['value', -1]]), new ThrowFactory(), -10);

        // when, then
        $this->assertNull($decorator->getGroup('group'));
    }

    /**
     * @test
     */
    public function test_getGroup_null()
    {
        // given
        $decorator = new GroupPolyfillDecorator(new ThrowFalseNegative(), new ConstantAll(['group' => [[null, -1]]]), 0);

        // when, then
        $this->assertNull($decorator->getGroup('group'));
    }

    private function match(array $match): FalseNegative
    {
        return new FalseNegative(new RawMatchOffset($match));
    }
}
