<?php
namespace Test\Unit\TRegx\SafeRegex\Errors\Errors;

use PHPUnit\Framework\TestCase;
use Test\Warnings;
use TRegx\SafeRegex\Errors\Errors\RuntimeErrorFactory;

class RuntimeErrorFactoryTest extends TestCase
{
    use Warnings;

    /**
     * @test
     */
    public function shouldGetLast()
    {
        // given
        $this->causeRuntimeWarning();

        // when
        $error = RuntimeErrorFactory::getLast();

        // then
        $this->assertTrue($error->occurred());

        // cleanup
        $error->clear();
    }

    /**
     * @test
     */
    public function shouldNotGetLast()
    {
        // when
        $error = RuntimeErrorFactory::getLast();

        // then
        $this->assertFalse($error->occurred());
    }
}
