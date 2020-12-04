<?php
namespace TRegx\SafeRegex\Exception\Factory;

use TRegx\SafeRegex\Constants\PhpErrorConstants;
use TRegx\SafeRegex\Exception\CompilePregException;
use TRegx\SafeRegex\Exception\MalformedPatternException;
use TRegx\SafeRegex\PhpError;

class CompilePregExceptionFactory
{
    /** @var PhpErrorConstants */
    private $phpErrorConstants;
    /** @var string */
    private $methodName;
    /** @var string|array */
    private $pattern;
    /** @var PhpError */
    private $error;

    public function __construct(string $methodName, $pattern, PhpError $error)
    {
        $this->phpErrorConstants = new PhpErrorConstants();
        $this->methodName = $methodName;
        $this->pattern = $pattern;
        $this->error = $error;
    }

    public function create(): CompilePregException
    {
        [$class, $message] = $this->exceptionClassAndMessage($this->error->getMessage());
        return new $class(
            $this->methodName,
            $this->pattern,
            $this->cleanMessage($message),
            $this->error,
            $this->phpErrorConstants->getConstant($this->error->getType()));
    }

    private function exceptionClassAndMessage(string $message): array
    {
        if ($this->matchMalformed($message, $result)) {
            return [MalformedPatternException::class, $result];
        }
        return [CompilePregException::class, $message];
    }

    private function matchMalformed(string $message, ?string &$result): bool
    {
        $pattern = '/^preg_(?:match(?:_all)?|replace(?:_callback(?:_array)?)?|filter|split|grep)\(\): (?:Compilation failed: )?(.*)/';
        if (\preg_match($pattern, $message, $match) === 1) {
            $result = \ucfirst($match[1]);
            return true;
        }
        return false;
    }

    private function cleanMessage(string $message): string
    {
        $value = \str_replace('(PCRE2_DUPNAMES not set) ', '', $message);

        if (\version_compare(\PHP_VERSION, '7.3.0', '<')) {
            if (\preg_match("/^Two named subpatterns have the same name at offset (\d+)$/", $value, $match)) {
                $offset = $match[1] + 1; // increase offset by 1, to fix php inconsistencies
                return "Two named subpatterns have the same name at offset $offset";
            }
        }

        return $value;
    }
}
