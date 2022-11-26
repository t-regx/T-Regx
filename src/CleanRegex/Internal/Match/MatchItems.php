<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Internal\Match\Flat\FlatFunction;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\CleanRegex\Internal\Subject;

class MatchItems
{
    /** @var ApiBase */
    private $base;
    /** @var DetailObjectFactory */
    private $factory;

    public function __construct(ApiBase $base, Subject $subject)
    {
        $this->base = $base;
        $this->factory = new DetailObjectFactory($subject);
    }

    public function filter(Predicate $predicate): array
    {
        return \array_values(\array_filter($this->getDetailObjects(), [$predicate, 'test']));
    }

    public function flatMap(FlatFunction $function): array
    {
        return $function->flatMap($this->getDetailObjects());
    }

    private function getDetailObjects(): array
    {
        return $this->factory->mapToDetailObjects($this->base->matchAllOffsets());
    }
}
