<?php
namespace TRegx\CleanRegex\Internal\Replace\By\GroupMapper;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\Replace\Map\ForGroupMessage;
use TRegx\CleanRegex\Internal\Message\Replace\Map\ForMatchMessage;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazySubjectRs;
use TRegx\CleanRegex\Match\Detail;

class SubstituteFallbackMapper implements DetailGroupMapper
{
    /** @var GroupMapper */
    private $mapper;
    /** @var LazySubjectRs */
    private $substitute;

    public function __construct(GroupMapper $mapper, LazySubjectRs $substitute)
    {
        $this->mapper = $mapper;
        $this->substitute = $substitute;
    }

    public function map(string $occurrence, Detail $initialDetail): ?string
    {
        $result = $this->mapper->map($occurrence, $initialDetail);
        if ($result === null) {
            return $this->substitute->substitute();
        }
        return $result;
    }

    public function useExceptionValues(string $occurrence, GroupKey $group, string $match): void
    {
        $this->substitute->useExceptionMessage($group->full()
            ? new ForMatchMessage($occurrence)
            : new ForGroupMessage($match, $group, $occurrence));
    }
}
