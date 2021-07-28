<?php
namespace Test\Unit\TRegx\CleanRegex\Exception\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;

/**
 * @covers \TRegx\CleanRegex\Exception\NonexistentGroupException
 */
class NonexistentGroupExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetMessage(): void
    {
        // given
        $exception = new NonexistentGroupException(new GroupName('name'));

        // when
        $message = $exception->getMessage();

        // then
        $this->assertSame("Nonexistent group: 'name'", $message);
    }
}
