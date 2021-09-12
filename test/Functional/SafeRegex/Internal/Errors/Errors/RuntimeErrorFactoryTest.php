<?php
namespace Test\Functional\TRegx\SafeRegex\Internal\Errors\Errors;

use PHPUnit\Framework\TestCase;
use Test\Utils\Warnings;
use TRegx\SafeRegex\Internal\Errors\Errors\RuntimeErrorFactory;

/**
 * @coversNothing
 */
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
