<?php
namespace Test\Unit\TRegx\CleanRegex\Exception\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;

class NonexistentGroupExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetMessage(): void
    {
        // given
        $exception = new NonexistentGroupException('name');

        // when
        $message = $exception->getMessage();

        // then
        $this->assertEquals("Nonexistent group: 'name'", $message);
    }
}
