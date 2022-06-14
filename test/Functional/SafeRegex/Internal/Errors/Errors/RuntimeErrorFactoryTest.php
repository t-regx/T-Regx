<?php
namespace Test\Functional\SafeRegex\Internal\Errors\Errors;

use PHPUnit\Framework\TestCase;
use Test\Utils\Runtime\CausesWarnings;
use TRegx\SafeRegex\Internal\Errors\Errors\RuntimeErrorFactory;

class RuntimeErrorFactoryTest extends TestCase
{
    use CausesWarnings;

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
