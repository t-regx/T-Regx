<?php
namespace TRegx\CleanRegex\Exception;

class TemplateFormatException extends PatternException
{
    public static function insufficient(int $tokens, int $placeholders): self
    {
        return new self("There are only $tokens & tokens in template, but $placeholders builder methods were used");
    }
    public static function superfluous(int $tokens, int $placeholders): self
    {
        return new self("There are $tokens & tokens in template, but only $placeholders builder methods were used");
    }
}
