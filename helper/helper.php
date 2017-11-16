<?php

if (!function_exists('pattern')) {
    function pattern(string $pattern): \CleanRegex\Pattern
    {
        return new \CleanRegex\Pattern($pattern);
    }
}
