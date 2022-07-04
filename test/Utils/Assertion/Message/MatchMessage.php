<?php
namespace Test\Utils\Assertion\Message;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchMessage
{
    /** @var MatchPattern */
    private $match;

    public function __construct(MatchPattern $match)
    {
        $this->match = $match;
    }

    public function missingGroupMessage(GroupKey $group): string
    {
        return "Failed to assert that group $group is missing from pattern {$this->pattern()}";
    }

    private function pattern(): string
    {
        return $this->baseDefinition($this->matchPatternBase($this->match))->pattern;
    }

    private function matchPatternBase(MatchPattern $match): ApiBase
    {
        return $this->property($match, 'base');
    }

    private function baseDefinition(ApiBase $base): Definition
    {
        return $this->property($base, 'definition');
    }

    private function property($object, string $property)
    {
        $closure = function ($property) {
            return $this->$property;
        };
        return $closure->call($object, $property);
    }
}
