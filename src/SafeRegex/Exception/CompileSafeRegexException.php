<?php
namespace SafeRegex\Exception;

use SafeRegex\Constants\PhpErrorConstants;
use SafeRegex\PhpError;

class CompileSafeRegexException extends SafeRegexException
{
    /** @var PhpError */
    private $error;

    public function __construct(string $methodName, PhpError $error)
    {
        $this->error = $error;

        parent::__construct($methodName, $this->formatMessage());
    }

    private function formatMessage(): string
    {
        return $this->getPregErrorMessage() . PHP_EOL . ' ' . PHP_EOL . '(caused by ' . $this->getErrorName() . ')';
    }

    public function getError(): int
    {
        return $this->error->getType();
    }

    public function getErrorName(): string
    {
        return (new PhpErrorConstants())->getConstant($this->error->getType());
    }

    public function getPregErrorMessage(): string
    {
        return $this->error->getMessage();
    }
}
