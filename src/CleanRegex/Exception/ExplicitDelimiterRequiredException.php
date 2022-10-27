<?php
namespace TRegx\CleanRegex\Exception;

class ExplicitDelimiterRequiredException extends \RuntimeException implements PatternException
{
    public function __construct(string $message)
    {
        parent::__construct("Failed to select a distinct delimiter to enable $message");
    }

    public static function forStandard(string $pattern): self
    {
        return new self("pattern: $pattern");
    }

    public static function forMask(array $keywords): self
    {
        return new self('mask keywords in their entirety: ' . \implode(', ', $keywords));
    }

    public static function forMaskKeyword(string $keyword, string $pattern): self
    {
        return new self("mask pattern '$pattern' assigned to keyword '$keyword'");
    }

    public static function forTemplate(): self
    {
        return new self('template in its entirety');
    }
}
