<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class PerformanceSignatures implements Signatures
{
    /** @var RawMatchOffset */
    private $rawMatchOffset;
    /** @var GroupAware */
    private $groupAware;

    public function __construct($rawMatchOffset, GroupAware $groupAware)
    {
        $this->rawMatchOffset = $rawMatchOffset;
        $this->groupAware = $groupAware;
    }

    public function signature(GroupKey $group): GroupSignature
    {
        try {
            return (new ArraySignatures($this->rawMatchOffset->getGroupKeys()))->signature($group);
        } catch (InsufficientMatchException $exception) {
            try {
                return (new ArraySignatures($this->groupAware->getGroupKeys()))->signature($group);
            } catch (InsufficientMatchException $exception) {
                throw new InternalCleanRegexException();
            }
        }
    }
}
