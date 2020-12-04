<?php
namespace Test\Functional\TRegx\SafeRegex\Errors\Errors;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Errors\Errors\CompileErrorFactory;
use TRegx\SafeRegex\Errors\Errors\StandardCompileError;

class CompileErrorFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetLast_standard()
    {
        // when
        $error = CompileErrorFactory::getLast();

        // then
        $this->assertInstanceOf(StandardCompileError::class, $error);
    }
}
