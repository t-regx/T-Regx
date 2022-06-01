<?php
namespace Test\Utils;

use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Pattern;

class ControledBacktracking
{
    public function match(): MatchPattern
    {
        return Pattern::of('(_+_+)+!')->match('__!, ____________, !');
    }

    public function inStrictEnvironment(callable $callable): void
    {
        ini_set('pcre.backtrack_limit', 1000);
        $callable();
        ini_set('pcre.backtrack_limit', 1000000);
    }
}
