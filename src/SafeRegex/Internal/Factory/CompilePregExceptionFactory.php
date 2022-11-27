<?php
namespace TRegx\SafeRegex\Internal\Factory;

use TRegx\SafeRegex\Exception\CompilePregException;
use TRegx\SafeRegex\Exception\PregMalformedPatternException;
use TRegx\SafeRegex\Internal\Constants\PhpErrorConstants;
use TRegx\SafeRegex\Internal\PhpError;

class CompilePregExceptionFactory
{
    /** @var PhpErrorConstants */
    private $phpErrorConstants;
    /** @var string */
    private $methodName;
    /** @var string|string[] */
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
        if ($this->matchMalformed($this->error->getMessage(), $malformedMessage)) {
            return new PregMalformedPatternException(
                $this->methodName,
                $this->pattern,
                $this->cleanMessage($malformedMessage),
                $this->error,
                $this->phpErrorConstants->getConstant($this->error->getType()));
        }
        return new CompilePregException(
            $this->methodName,
            $this->pattern,
            $this->cleanMessage($this->error->getMessage()),
            $this->error,
            $this->phpErrorConstants->getConstant($this->error->getType()));
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
        if ($message === 'Null byte in regex') {
            return 'Pattern may not contain null-byte';
        }
        if ($message === 'Delimiter must not be alphanumeric, backslash, or NUL') {
            return 'Delimiter must not be alphanumeric or backslash';
        }
        $message = \str_replace('(PCRE2_DUPNAMES not set) ', '', $message);
        $message = \str_replace('Unrecognized character after (?< at offset ', 'Subpattern name expected at offset ', $message);

        if (\version_compare(\PHP_VERSION, '7.3.0', '<')) {
            if (\preg_match("/^Two named subpatterns have the same name at offset (\d+)$/", $message, $match)) {
                $offset = $match[1] + 1; // increase offset by 1, to fix php inconsistencies
                return "Two named subpatterns have the same name at offset $offset";
            }
            if (\preg_match("/^Nothing to repeat at offset (\d+)$/", $message, $match)) {
                $offset = $match[1];
                return "Quantifier does not follow a repeatable item at offset $offset";
            }
        }

        return $message;
    }
}
