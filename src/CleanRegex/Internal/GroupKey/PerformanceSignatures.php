<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Exception\InsufficientMatchException;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class PerformanceSignatures
{
    /** @var RawMatchOffset */
    private $rawMatchOffset;
    /** @var GroupAware */
    private $groupAware;

    public function __construct(RawMatchOffset $rawMatchOffset, GroupAware $groupAware)
    {
        $this->rawMatchOffset = $rawMatchOffset;
        $this->groupAware = $groupAware;
    }

    public function signature(GroupKey $group): GroupSignature
    {
        try {
            return (new Signatures($this->rawMatchOffset->getGroupKeys()))->signature($group->nameOrIndex());
        } catch (InsufficientMatchException $exception) {
            try {
                return (new Signatures($this->groupAware->getGroupKeys()))->signature($group->nameOrIndex());
            } catch (InsufficientMatchException $exception) {
                throw new InternalCleanRegexException();
            }
        }
    }
}
