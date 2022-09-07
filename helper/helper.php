<?php

if (!function_exists('pattern')) {
    function pattern(string $pattern, string $modifiers = null): \TRegx\CleanRegex\Pattern
    {
        return \TRegx\CleanRegex\Pattern::of($pattern, $modifiers);
    }
}
