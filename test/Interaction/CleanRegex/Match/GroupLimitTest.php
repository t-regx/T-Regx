<?php
namespace Test\Interaction\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\DetailGroup;

class GroupLimitTest extends TestCase
{
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
        $this->assertEquals('Foo Bar', $result);
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
            $this->assertEquals(4, $exception->getIndex());
            $this->assertEquals(1, $exception->getTotal());
            $this->assertEquals("Expected to get group 'foo' from the 4-nth match, but only 1 occurrences were matched", $exception->getMessage());
        }
    }
}
