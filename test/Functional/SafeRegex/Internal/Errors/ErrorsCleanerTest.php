<?php
namespace Test\Functional\SafeRegex\Internal\Errors;

use PHPUnit\Framework\TestCase;
use Test\Utils\Runtime\CausesWarnings;
use TRegx\SafeRegex\Internal\Errors\CompileError;

class ErrorsCleanerTest extends TestCase
{
    use CausesWarnings;

    /**
     * @test
     * @see https://bugs.php.net/bug.php?id=74183
     */
    public function shouldGetCompileOrBothError()
    {
        $this->markTestIncomplete();
        // given
        $this->causeMalformedPatternWarning();

        // when
        $error = $cleaner->getError();

        // then
        $this->assertInstanceOf($this->isBugFixed() ? 'BothHostError::class' : CompileError::class, $error);
        $this->assertTrue($error->occurred());

        // cleanup
        $error->clear();
    }

    private function isBugFixed(): bool
    {
        if (PHP_VERSION_ID === 70200) {
            return false;
        }
        return PHP_VERSION_ID >= 70113;
    }
}
