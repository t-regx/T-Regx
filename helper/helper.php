<?php

if (!function_exists('pattern')) {
    function pattern(string $pattern, string $flags = ''): \TRegx\CleanRegex\Pattern
    {
        return \TRegx\CleanRegex\Pattern::of($pattern, $flags);
    }
}
