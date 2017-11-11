<?php

if (!function_exists('pattern')) {
    function pattern(string $pattern): \Danon\CleanRegex\Pattern
    {
        return new \Danon\CleanRegex\Pattern($pattern);
    }
}

if (!function_exists('pcre')) {
    function pcre(string $pattern): \Danon\CleanRegex\Pattern
    {
        return new \Danon\CleanRegex\Pattern($pattern);
    }
}
