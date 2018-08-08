<?php

if (!function_exists('pattern')) {
    function pattern(string $pattern, string $flags = ''): \CleanRegex\Pattern
    {
        return new \CleanRegex\Pattern($pattern, $flags);
    }
}
