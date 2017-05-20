<?php

use Danon\CleanRegex\Pattern;

if (!function_exists('pattern')) {
    function pattern(string $pattern): Pattern
    {
        return new Pattern($pattern);
    }
}
