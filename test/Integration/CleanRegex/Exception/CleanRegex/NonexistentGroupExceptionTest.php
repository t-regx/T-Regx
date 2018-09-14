<?php
namespace Test\Integration\CleanRegex\Exception\CleanRegex;

use CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use PHPUnit\Framework\TestCase;

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
        $this->assertEquals('Nonexistent group: name', $message);
    }
}
