<?php
namespace TRegx\CleanRegex\Match\Groups\Strategy;

use TRegx\CleanRegex\Internal\Delimiter\DelimiterParser;
use InvalidArgumentException;
use TRegx\SafeRegex\preg;

class PatternAnalyzeGroupVerifier implements GroupVerifier
{
    /** @var DelimiterParser */
    private $parser;

    public function __construct(DelimiterParser $parser)
    {
        $this->parser = $parser;
    }

    public function groupExists(string $pattern, $nameOrIndex): bool
    {
        if (is_string($nameOrIndex)) {
            return $this->groupNameExists($pattern, $nameOrIndex);
        }
        throw new InvalidArgumentException('Analyzing pattern is supported only for string group names');
    }

    private function groupNameExists(string $pattern, string $name): bool
    {
        $d = $this->parser->getDelimiter($pattern);
        $p1 = preg_quote("(?<$name>", $d);
        $p2 = preg_quote("(?P<$name>", $d);
        $p3 = preg_quote("(?'$name'", $d);
        $p = "($p1|$p2|$p3)";

        return preg::match("/(?<!\\\\)$p/", $pattern);
    }
}
