<?php
namespace Test\Interaction\TRegx\CleanRegex\Replace\By;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use Test\Utils\Internal;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\NonReplacedMessage;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DetailGroupMapper;
use TRegx\CleanRegex\Internal\Replace\By\IdentityWrapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\By\UnmatchedGroupStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Subject;

class UnmatchedGroupStrategyTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Test\DataProviders::groupReplaceFallbacks()
     * @param string $method
     * @param array $arguments
     */
    public function shouldReturn(string $method, array $arguments)
    {
        // given
        $strategy = $this->objectUnderTest('length: 14cm!');

        // when
        $result = $strategy->$method(...$arguments);

        // then
        $this->assertSame('length: 14cm!', $result);
    }

    /**
     * @test
     */
    public function should_OrElseWith()
    {
        // given
        $strategy = $this->objectUnderTest('length: 14!');

        // when
        $result = $strategy->orElseWith('with');

        // then
        $this->assertSame('length: with!', $result);
    }

    /**
     * @test
     */
    public function should_OrElseThrow()
    {
        // given
        $strategy = $this->objectUnderTest('length: 14!');

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'group', but the group was not matched");

        // when
        $strategy->orElseThrow();
    }

    /**
     * @test
     */
    public function should_OrElseThrow_custom()
    {
        // given
        $strategy = $this->objectUnderTest('length: 14!');

        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage("Expected to replace with group 'group', but the group was not matched");

        // when
        $strategy->orElseThrow(CustomSubjectException::class);
    }

    /**
     * @test
     */
    public function should_OrElseEmpty()
    {
        // given
        $strategy = $this->objectUnderTest('length: 14!');

        // when
        $result = $strategy->orElseEmpty();

        // then
        $this->assertSame('length: !', $result);
    }

    /**
     * @test
     */
    public function should_OrElseIgnore()
    {
        // given
        $strategy = $this->objectUnderTest('length: 14!');

        // when
        $result = $strategy->orElseIgnore();

        // then
        $this->assertSame('length: 14!', $result);
    }

    /**
     * @test
     */
    public function should_OrElseCalling()
    {
        // given
        $strategy = $this->objectUnderTest('length: 14!');

        // when
        $result = $strategy->orElseCalling(Functions::constant('called'));

        // then
        $this->assertSame('length: called!', $result);
    }

    public function objectUnderTest($subject): UnmatchedGroupStrategy
    {
        return new UnmatchedGroupStrategy($this->replacer($subject), 'group',
            $this->createMock(DetailGroupMapper::class), new IdentityWrapper());
    }

    public function replacer(string $subject): GroupFallbackReplacer
    {
        $pattern = Internal::pattern('\d+(?<group>cm)?');
        return new GroupFallbackReplacer(
            $pattern,
            new Subject($subject),
            -1,
            new ThrowStrategy(\AssertionError::class, new NonReplacedMessage()), // anything
            new IgnoreCounting(),
            new ApiBase($pattern, $subject, new UserData())
        );
    }
}
