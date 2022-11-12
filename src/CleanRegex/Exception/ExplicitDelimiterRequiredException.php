<?php
namespace TRegx\CleanRegex\Exception;

class ExplicitDelimiterRequiredException extends \RuntimeException implements PatternException
{
    public function __construct(string $template)
    {
        parent::__construct("Failed to select a distinct delimiter to enable $template");
    }
}
