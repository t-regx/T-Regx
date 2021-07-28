<?php
namespace Test\Interaction\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Impl\ConstantAllBase;
use Test\Utils\Impl\GroupKeys;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Match\GroupLimit;

/**
 * @covers \TRegx\CleanRegex\Match\GroupLimit
 */
class GroupLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFlatMap()
    {
        // given
        $matches = [['Foo Bar', 1], ['Lorem', 1]];
        $limit = self::groupLimitAll($matches, 'value');

        // when
        $result = $limit->flatMap(Functions::letters());

        // then
        $this->assertSame(['F', 'o', 'o', ' ', 'B', 'a', 'r', 'L', 'o', 'r', 'e', 'm'], $result);
    }

    /**
     * @test
     */
    public function shouldFlatMapAssoc()
    {
        // given
        $matches = [['Lorem', 1], ['Dog', 1], ['C', 1]];
        $limit = self::groupLimitAll($matches, 'value');

        // when
        $result = $limit->flatMapAssoc(Functions::letters());

        // then
        $this->assertSame(['C', 'o', 'g', 'e', 'm'], $result);
    }

    /**
     * @test
     */
    public function shouldThrow_flatMap_forInvalidArgument()
    {
        // given
        $limit = self::groupLimitAll(['Foo Bar', 1], 0, 'subject');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage("Invalid flatMap() callback return type. Expected array, but string ('text') given");

        // when
        $limit->flatMap(Functions::constant('text'));
    }

    /**
     * @test
     */
    public function shouldThrow_flatMapAssoc_forInvalidArgument()
    {
        // given
        $limit = self::groupLimitAll(['Foo Bar', 1], 0, 'subject');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage("Invalid flatMapAssoc() callback return type. Expected array, but string ('word') given");

        // when
        $limit->flatMapAssoc(Functions::constant('word'));
    }

    /**
     * @test
     */
    public function shouldGet_nth_forIndex0()
    {
        // given
        $limit = self::groupLimitAll([['Foo Bar', 1]], 'lorem');

        // when
        $result = $limit->nth(0);

        // then
        $this->assertSame('Foo Bar', $result);
    }

    /**
     * @test
     */
    public function shouldNth_forIndex1()
    {
        // given
        $limit = self::groupLimitAll([['Foo Bar', 1]], 'lorem');

        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get group 'lorem' from the 1-nth match, but only 1 occurrences were matched");

        // when
        $limit->nth(1);
    }

    /**
     * @test
     */
    public function shouldNth_forIndex2()
    {
        // given
        $limit = self::groupLimitAll([['Foo Bar', 1], ['Lorem', 2]], 3);

        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get group #3 from the 2-nth match, but only 2 occurrences were matched");

        // when
        $limit->nth(2);
    }

    /**
     * @test
     */
    public function shouldNth_forUnmatchedSubject()
    {
        // given
        $limit = self::groupLimitAll([], 'value', 'subject');

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'value' from the 3-nth match, but the subject was not matched");

        // when
        $limit->nth(3);
    }

    /**
     * @test
     */
    public function shouldNth_forNegativeIndex()
    {
        // given
        $limit = self::groupLimitAll([]);

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Negative group nth: -3");

        // when
        $limit->nth(-3);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forSubjectNotMatched()
    {
        // given
        $limit = new GroupLimit(new ConstantAllBase($this->rawMatches([], 'name'), 'subject'), new GroupKeys(['name']), 'name');

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'name' from the 5-nth match, but the subject was not matched");

        // when
        $limit->nth(5);
    }

    public function groupLimitAll(array $allValues, $nameOrIndex = 0, string $subject = null): GroupLimit
    {
        return new GroupLimit(new ConstantAllBase($this->rawMatches($allValues, $nameOrIndex), $subject), new GroupKeys([$nameOrIndex]), $nameOrIndex);
    }

    private function rawMatches(array $allValues, $nameOrIndex): RawMatchesOffset
    {
        return new RawMatchesOffset([0 => $allValues, $nameOrIndex => $allValues, 1 => $allValues]);
    }
}
