<?php
namespace Test\SafeRegex;

use PHPUnit\Framework\TestCase;
use SafeRegex\Exception\PhpErrorSafeRegexException;
use SafeRegex\Exception\RuntimeSafeRegexException;
use SafeRegex\Guard\GuardedExecution;
use Test\Warnings;

class GuardedExecutionTest extends TestCase
{
    use Warnings;

    /**
     * @test
     * @dataProvider possibleObsoleteWarnings
     * @param callable $obsoleteWarning
     */
    public function shouldIgnorePreviousWarnings(callable $obsoleteWarning)
    {
        // given
        call_user_func($obsoleteWarning);

        // when
        $invocation = GuardedExecution::catch('preg_match', function () {
            return 1;
        });

        // then
        $this->assertNull($invocation->getException());
        $this->assertFalse($invocation->hasException());
    }

    public function possibleObsoleteWarnings()
    {
        return [
            [function () {
                $this->causeRuntimeWarning();
            }],
            [function () {
                $this->causePhpWarning();
            }],
        ];
    }

    /**
     * @test
     */
    public function shouldCatchRuntimeWarning()
    {
        // when
        $invocation = GuardedExecution::catch('preg_match', function () {
            $this->causeRuntimeWarning();
            return false;
        });

        // then
        $this->assertTrue($invocation->hasException());
        $this->assertInstanceOf(RuntimeSafeRegexException::class, $invocation->getException());
    }

    /**
     * @test
     */
    public function shouldCatchPhpWarning()
    {
        // when
        $invocation = GuardedExecution::catch('preg_match', function () {
            $this->causePhpWarning();
            return false;
        });

        // then
        $this->assertTrue($invocation->hasException());
        $this->assertInstanceOf(PhpErrorSafeRegexException::class, $invocation->getException());
    }
}
