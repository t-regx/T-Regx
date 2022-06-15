<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchItems
{
    /** @var ApiBase */
    private $base;
    /** @var Subject */
    private $subject;

    public function __construct(ApiBase $base, Subject $subject)
    {
        $this->base = $base;
        $this->subject = $subject;
    }

    public function map(callable $mapper): array
    {
        return \array_map($mapper, $this->getDetailObjects());
    }

    public function filter(Predicate $predicate): array
    {
        return \array_values(\array_map(static function (Detail $detail): string {
            return $detail->text();
        }, \array_filter($this->getDetailObjects(), [$predicate, 'test'])));
    }

    public function flatMap(FlatFunction $function): array
    {
        return (new ArrayMergeStrategy())->flatten($function->map($this->getDetailObjects()));
    }

    public function flatMapAssoc(FlatFunction $function): array
    {
        return (new AssignStrategy())->flatten($function->map($this->getDetailObjects()));
    }

    private function getDetailObjects(): array
    {
        $factory = new DetailObjectFactory($this->subject);
        return $factory->mapToDetailObjects($this->base->matchAllOffsets());
    }
}
