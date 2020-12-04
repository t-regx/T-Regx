<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Model;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class GroupPolyfillDecoratorTest extends TestCase
{
    /**
     * @test
     */
    public function test_hasGroup_true()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->match(['group' => 'value']), $this->noInteraction(), -10);

        // when + then
        $this->assertTrue($decorator->hasGroup('group'));
    }

    /**
     * @test
     */
    public function test_hasGroup_call()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->empty(), $this->all(['group' => null]), -10);

        // when + then
        $this->assertTrue($decorator->hasGroup('group'));
    }

    /**
     * @test
     */
    public function test_hasGroup_false()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->empty(), $this->all([]), -10);

        // when + then
        $this->assertFalse($decorator->hasGroup('group'));
    }

    /**
     * @test
     */
    public function test_matched_true()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->match(['group' => 'value']), $this->noInteraction(), -10);

        // when + then
        $this->assertTrue($decorator->matched());
    }

    /**
     * @test
     */
    public function test_matched_false()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->empty(), $this->noInteraction(), -10);

        // when + then
        $this->assertFalse($decorator->matched());
    }

    /**
     * @test
     */
    public function test_isGroupMatched_true()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->match(['group' => ['value', 0]]), $this->noInteraction(), -10);

        // when + then
        $this->assertTrue($decorator->isGroupMatched('group'));
    }

    /**
     * @test
     */
    public function test_isGroupMatched_call()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->empty(), $this->all(['group' => [['value', 0]]]), 0);

        // when + then
        $this->assertTrue($decorator->isGroupMatched('group'));
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
        $decorator = new GroupPolyfillDecorator($this->match(['group' => ['value', -1]]), $this->noInteraction(), -10);

        // when + then
        $this->assertFalse($decorator->isGroupMatched('group'));
    }

    /**
     * @test
     */
    public function test_isGroupMatched_false()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->empty(), $this->all(['group' => [[null, -1]]]), 0);

        // when + then
        $this->assertFalse($decorator->isGroupMatched('group'));
    }

    /**
     * @test
     */
    public function test_getGroupTextAndOffset()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->match(['group' => ['Foo', 2]]), $this->noInteraction(), -10);

        // when + then
        $this->assertSame(['Foo', 2], $decorator->getGroupTextAndOffset('group'));
    }

    /**
     * @test
     */
    public function test_getGroupTextAndOffset_call()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->empty(), $this->all(['group' => [['Foo', 2]]]), 0);

        // when + then
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
        $decorator = new GroupPolyfillDecorator($this->match(['group' => ['value', -1]]), $this->noInteraction(), -10);

        // when + then
        $this->assertSame(['value', -1], $decorator->getGroupTextAndOffset('group'));
    }

    /**
     * @test
     */
    public function test_getText()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->match([0 => ['value', -10]]), $this->noInteraction(), -10);

        // when + then
        $this->assertSame('value', $decorator->getText());
    }

    /**
     * @test
     */
    public function test_getByteOffset()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->match([0 => ['value', 15]]), $this->noInteraction(), -10);

        // when + then
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
        $decorator = new GroupPolyfillDecorator($this->match([0 => ['one', 15]]), $this->all($matches), 0);

        // when + then
        $this->assertSame(['foo' => 'bar', 'lorem' => 'ipsum'], $decorator->getGroupsTexts());
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
        $decorator = new GroupPolyfillDecorator($this->match([0 => ['one', 0]]), $this->all($matches), 0);

        // when + then
        $this->assertSame(['foo' => 10, 'lorem' => 15], $decorator->getGroupsOffsets());
    }

    /**
     * @test
     */
    public function test_getGroupKeys()
    {
        // given
        $matches = [
            'foo'   => [['bar', 10]],
            'lorem' => [['ipsum', 15]],
        ];
        $decorator = new GroupPolyfillDecorator($this->match([0 => ['one', 0]]), $this->all($matches), 0);

        // when + then
        $this->assertSame(['foo', 'lorem'], $decorator->getGroupKeys());
    }

    /**
     * @test
     */
    public function test_getGroup_true()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->match(['group' => ['value', 0]]), $this->noInteraction(), -10);

        // when + then
        $this->assertSame('value', $decorator->getGroup('group'));
    }

    /**
     * @test
     */
    public function test_getGroup_call()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->empty(), $this->all(['group' => [['value', 0]]]), 0);

        // when + then
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
        $decorator = new GroupPolyfillDecorator($this->match(['group' => ['value', -1]]), $this->noInteraction(), -10);

        // when + then
        $this->assertNull($decorator->getGroup('group'));
    }

    /**
     * @test
     */
    public function test_getGroup_null()
    {
        // given
        $decorator = new GroupPolyfillDecorator($this->empty(), $this->all(['group' => [[null, -1]]]), 0);

        // when + then
        $this->assertNull($decorator->getGroup('group'));
    }

    private function match(array $match): IRawMatchOffset
    {
        return new RawMatchOffset($match);
    }

    private function empty(): IRawMatchOffset
    {
        return new RawMatchOffset([]);
    }

    private function noInteraction(): MatchAllFactory
    {
        /** @var MatchAllFactory|MockObject $factory */
        $factory = $this->createMock(MatchAllFactory::class);
        $factory->expects($this->never())->method($this->anything());
        return $factory;
    }

    private function all(array $matches): MatchAllFactory
    {
        /** @var MatchAllFactory|MockObject $factory */
        $factory = $this->createMock(MatchAllFactory::class);
        $factory->expects($this->once())->method('getRawMatches')->willReturn(new RawMatchesOffset($matches));
        return $factory;
    }
}
