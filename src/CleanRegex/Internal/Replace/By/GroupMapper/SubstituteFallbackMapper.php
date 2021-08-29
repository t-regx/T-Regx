<?php
namespace TRegx\CleanRegex\Internal\Replace\By\GroupMapper;

use TRegx\CleanRegex\Internal\Exception\Messages\MissingReplacement\ForGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\MissingReplacement\ForMatchMessage;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazySubjectRs;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;

class SubstituteFallbackMapper implements DetailGroupMapper
{
    /** @var GroupMapper */
    private $mapper;
    /** @var LazySubjectRs */
    private $substitute;
    /** @var Subject */
    private $subject;

    public function __construct(GroupMapper $mapper, LazySubjectRs $substitute, Subject $subject)
    {
        $this->mapper = $mapper;
        $this->substitute = $substitute;
        $this->subject = $subject;
    }

    public function map(string $occurrence, Detail $initialDetail): ?string
    {
        $result = $this->mapper->map($occurrence, $initialDetail);
        if ($result === null) {
            return $this->substitute->substitute($this->subject);
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
