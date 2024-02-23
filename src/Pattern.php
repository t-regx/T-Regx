<?php
namespace Regex;

use Regex\Internal\Delimiter;
use Regex\Internal\ExceptionFactory;
use Regex\Internal\Modifiers;
use Regex\Internal\OptionSettingExpression;

final class Pattern extends Internal\CompiledPattern implements Regex
{
    public const IGNORE_CASE = 'i';
    public const MULTILINE = 'm';
    public const UNICODE = 'u';
    public const COMMENTS_WHITESPACE = 'x';
    public const EXPLICIT_CAPTURE = 'n';
    public const SINGLELINE = 's';
    public const ANCHORED = 'A';
    public const INVERTED_GREEDY = 'U';
    public const DUPLICATE_NAMES = 'J';

    public function __construct(string $pattern, string $modifiers = '')
    {
        $delimiter = new Delimiter($pattern);
        $extraModifiers = new Modifiers($modifiers);
        $expression = new OptionSettingExpression($pattern, $extraModifiers);
        parent::__construct(
            $delimiter . $expression . $delimiter . $extraModifiers,
            new ExceptionFactory($pattern, $expression));
    }
}
