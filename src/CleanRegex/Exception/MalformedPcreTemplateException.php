<?php
namespace TRegx\CleanRegex\Exception;

class MalformedPcreTemplateException extends PatternMalformedPatternException
{
    public function __construct(string $message)
    {
        parent::__construct("PCRE-compatible template is malformed, $message");
    }

    public static function emptyPattern(): self
    {
        return new self('pattern is empty');
    }

    public static function invalidDelimiter(string $delimiter): self
    {
        if (\ctype_alnum($delimiter)) {
            return new self("alphanumeric delimiter '$delimiter'");
        }
        return new self("starting with an unexpected delimiter '$delimiter'");
    }

    public static function unclosed(string $delimiter): self
    {
        return new self("unclosed pattern '$delimiter'");
    }
}
