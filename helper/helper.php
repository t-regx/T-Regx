<?php

if (!function_exists('pattern')) {
    function pattern(string $pattern): \Danon\CleanRegex\Pattern
    {
        return new \Danon\CleanRegex\Pattern($pattern);
    }
}
