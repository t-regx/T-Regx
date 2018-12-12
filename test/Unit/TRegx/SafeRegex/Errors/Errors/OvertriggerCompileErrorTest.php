<?php
namespace Test\Unit\TRegx\SafeRegex\Errors\Errors;

use PHPUnit\Framework\TestCase;
use Test\Warnings;
use TRegx\SafeRegex\Errors\Errors\OvertriggerCompileError;
use TRegx\SafeRegex\Errors\Errors\StandardCompileError;
use TRegx\SafeRegex\Exception\CompileSafeRegexException;
use TRegx\SafeRegex\PhpError;

class OvertriggerCompileErrorTest extends TestCase
{
    use Warnings;

    /**
     * @test
     */
    public function shouldNotOccur_forNoError()
    {
        // given
        $error = new OvertriggerCompileError(null);

        // when
        $occurred = $error->occurred();

        // then
        $this->assertFalse($occurred);
    }

    /**
     * @test
     */
    public function shouldNotOccur_forOvertriggeredError()
    {
        // given
        $error = new OvertriggerCompileError($this->phpError(OvertriggerCompileError::OVERTRIGGER_MESSAGE));

        // when
        $occurred = $error->occurred();

        // then
        $this->assertFalse($occurred);
    }

    /**
     * @test
     */
    public function shouldOccur_forOtherError()
    {
        // given
        $error = new OvertriggerCompileError($this->phpError('other error'));

        // when
        $occurred = $error->occurred();

        // then
        $this->assertTrue($occurred);
    }

    /**
     * @test
     */
    public function shouldClear()
    {
        // given
        $error = new OvertriggerCompileError($this->phpError('other error'));
        $this->causeCompileWarning();

        // when
        $error->clear();

        // then
        $lastError = PhpError::fromArray(error_get_last());
        $this->assertEquals(OvertriggerCompileError::OVERTRIGGER_MESSAGE, $lastError->getMessage());

        // clean up
        (new StandardCompileError($lastError))->clear();
    }

    /**
     * @test
     */
    public function shouldGetSafeRegexException()
    {
        // given
        $error = new OvertriggerCompileError($this->phpError('other error'));

        // when
        $exception = $error->getSafeRegexpException('preg_replace');

        // then
        $this->assertInstanceOf(CompileSafeRegexException::class, $exception);
        $this->assertEquals('preg_replace', $exception->getInvokingMethod());
        $this->assertEquals('other error' . PHP_EOL . ' ' . PHP_EOL . '(caused by E_WARNING)', $exception->getMessage());
    }

    private function phpError(string $message)
    {
        return PhpError::fromArray([
            'message' => $message,
            'type'    => E_WARNING,
            'file'    => '',
            'line'    => 0,
        ]);
    }
}
