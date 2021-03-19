<?php
namespace TRegx\CleanRegex\Exception;

class TemplateFormatException extends \Exception implements PatternException
{
    public static function insufficient(int $placeholders, int $tokens): self
    {
        return new self("There are only $placeholders & tokens in template, but $tokens builder methods were used");
    }

    public static function superfluous(int $placeholders, int $tokens): self
    {
        return new self("There are $placeholders & tokens in template, but only $tokens builder methods were used");
    }
}
