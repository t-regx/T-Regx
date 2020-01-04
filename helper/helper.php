<?php

if (!function_exists('pattern')) {
    function pattern(string $pattern, string $flags = ''): \TRegx\CleanRegex\PatternInterface
    {
        return \TRegx\CleanRegex\Pattern::of($pattern, $flags);
    }
}
