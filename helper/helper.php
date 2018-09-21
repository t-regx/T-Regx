<?php

if (!function_exists('pattern')) {
    function pattern(string $pattern, string $flags = ''): \TRegx\CleanRegex\Pattern
    {
        return new \TRegx\CleanRegex\Pattern($pattern, $flags);
    }
}
