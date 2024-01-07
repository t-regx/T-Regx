<?php
namespace Regex\Internal;

class DelimitedExpression
{
    public string $delimited;
    /** @var int[]|string[] */
    public array $groupKeys;

    public function __construct(string $pattern, Modifiers $modifiers)
    {
        $delimiter = new Delimiter($pattern);
        $expression = new OptionSettingExpression($pattern, $modifiers);
        $this->delimited = $delimiter . $expression . $delimiter . $modifiers;
        $parsed = new ParsedPattern($this->delimited);
        if ($parsed->errorMessage) {
            $factory = new ExceptionFactory($pattern, $expression);
            throw $factory->exceptionFor($parsed->errorMessage);
        }
        $this->groupKeys = $parsed->groupKeys();
    }
}
