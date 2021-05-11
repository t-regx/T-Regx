<?php

if (!function_exists('pattern')) {
    function pattern(string $pattern, string $flags = null): \TRegx\CleanRegex\PatternInterface
    {
        return \TRegx\CleanRegex\Pattern::of($pattern, $flags);
    }
}
