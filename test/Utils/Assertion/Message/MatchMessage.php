<?php
namespace Test\Utils\Assertion\Message;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Match\Matcher;

class MatchMessage
{
    /** @var Matcher */
    private $matcher;

    public function __construct(Matcher $matcher)
    {
        $this->matcher = $matcher;
    }

    public function missingGroupMessage(GroupKey $group): string
    {
        return "Failed to assert that group $group is missing from pattern {$this->pattern()}";
    }

    private function pattern(): string
    {
        return $this->baseDefinition($this->matchPatternBase($this->matcher))->pattern;
    }

    private function matchPatternBase(Matcher $match): ApiBase
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
