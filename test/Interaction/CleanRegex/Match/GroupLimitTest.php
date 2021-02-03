<?php
namespace Test\Interaction\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;

class GroupLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFlatMap()
    {
        // given
        $matches = [['Foo Bar', 1], ['Lorem', 1]];
        $limit = GroupLimitFactory::groupLimitAll($this, $matches, 'value');

        // when
        $result = $limit->flatMap('str_split');

        // then
        $this->assertSame(['F', 'o', 'o', ' ', 'B', 'a', 'r', 'L', 'o', 'r', 'e', 'm'], $result);
    }

    /**
     * @test
     */
    public function shouldGetFlatMapAssoc()
    {
        // given
        $matches = [['Lorem', 1], ['Dog', 1], ['C', 1]];
        $limit = GroupLimitFactory::groupLimitAll($this, $matches, 'value');

        // when
        $result = $limit->flatMapAssoc('str_split');

        // then
        $this->assertSame(['C', 'o', 'g', 'e', 'm'], $result);
    }

    /**
     * @test
     */
    public function shouldThrow_flatMap_forInvalidArgument()
    {
        // given
        $limit = GroupLimitFactory::groupLimitAll($this, ['Foo Bar', 1]);

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
        $limit = GroupLimitFactory::groupLimitAll($this, ['Foo Bar', 1]);

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
        $limit = GroupLimitFactory::groupLimitAll($this, [['Foo Bar', 1]], 'lorem');

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
        $limit = GroupLimitFactory::groupLimitAll($this, [['Foo Bar', 1]], 'lorem');

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
        $limit = GroupLimitFactory::groupLimitAll($this, [['Foo Bar', 1], ['Lorem', 2]], 3);

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
        $limit = GroupLimitFactory::groupLimitAll($this, [], 'value');

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
        $limit = GroupLimitFactory::groupLimitAll($this, []);

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
        $limit = GroupLimitFactory::groupLimitAll($this, [], 'name');

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'name' from the 5-nth match, but the subject was not matched");

        // when
        $limit->nth(5);
    }

    /**
     * @test
     */
    public function shouldExceptionContainDetails()
    {
        // given
        $limit = GroupLimitFactory::groupLimitAll($this, [['Foo', 0]], 'foo');

        try {
            // when
            $limit->nth(4);
            $this->fail();
        } catch (NoSuchNthElementException $exception) {
            // then
            $this->assertSame(4, $exception->getIndex());
            $this->assertSame(1, $exception->getTotal());
            $this->assertSame("Expected to get group 'foo' from the 4-nth match, but only 1 occurrences were matched", $exception->getMessage());
        }
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_filter_first()
    {
        // given
        $limit = GroupLimitFactory::groupLimitAll($this, [['12', 0]], 'foo');

        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the first element from fluent pattern, but the elements feed is empty');

        // when
        $limit->asInt()->filter(Functions::constant(false))->first();
    }
}
