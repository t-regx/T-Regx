<?php
namespace TRegx\SafeRegex\Internal\Errors;

use TRegx\SafeRegex\Internal\Errors\Errors\BothHostError;
use TRegx\SafeRegex\Internal\Errors\Errors\CompileError;
use TRegx\SafeRegex\Internal\Errors\Errors\EmptyHostError;
use TRegx\SafeRegex\Internal\Errors\Errors\IrrelevantCompileError;
use TRegx\SafeRegex\Internal\Errors\Errors\RuntimeError;
use TRegx\SafeRegex\Internal\Errors\Errors\StandardCompileError;
use TRegx\SafeRegex\Internal\PhpError;

class ErrorsCleaner
{
    public function clear(): void
    {
        $error = $this->getError();
        if ($error->occurred()) {
            $error->clear();
        }
    }

    public function getError(): HostError
    {
        $compile = $this->nullableError(\error_get_last());
        $runtime = new RuntimeError(\preg_last_error());

        if ($runtime->occurred() && $compile->occurred()) {
            return new BothHostError($compile, $runtime);
        }

        if ($compile->occurred()) {
            return $compile;
        }
        if ($runtime->occurred()) {
            return $runtime;
        }

        return new EmptyHostError();
    }

    private function nullableError(?array $error): CompileError
    {
        if ($error === null) {
            return new StandardCompileError(null);
        }
        return self::mapToError($error['type'], $error['message']);
    }

    private function mapToError(int $type, string $message): CompileError
    {
        $phpError = new PhpError($type, $message);
        if ($phpError->isPregError()) {
            return new StandardCompileError($phpError);
        }
        return new IrrelevantCompileError();
    }
}
