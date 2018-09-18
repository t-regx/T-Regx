<?php
namespace Test\Unit\SafeRegex\Errors\Errors;

use PHPUnit\Framework\TestCase;
use SafeRegex\Errors\Errors\OvertriggerCompileError;
use SafeRegex\Errors\Errors\StandardCompileError;
use SafeRegex\PhpError;
use Test\Warnings;

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
        $error = new OvertriggerCompileError($lastError);
        $this->assertFalse($error->occurred());

        // clean up
        (new StandardCompileError($lastError))->clear();
    }

    private function phpError(string $message)
    {
        return PhpError::fromArray([
            'message' => $message,
            'type' => E_WARNING,
            'file' => '',
            'line' => 0,
        ]);
    }
}
