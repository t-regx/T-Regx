<?php
namespace TRegx\CleanRegex\Internal\Pcre\Signatures;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\GroupSignature;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Model\GroupKeys;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchOffset;

class PerformanceSignatures implements Signatures
{
    /** @var RawMatchOffset */
    private $rawMatchOffset;
    /** @var GroupKeys */
    private $groupKeys;

    public function __construct($rawMatchOffset, GroupKeys $groupKeys)
    {
        $this->rawMatchOffset = $rawMatchOffset;
        $this->groupKeys = $groupKeys;
    }

    public function signature(GroupKey $group): GroupSignature
    {
        try {
            return (new ArraySignatures($this->rawMatchOffset->getGroupKeys()))->signature($group);
        } catch (InsufficientMatchException $exception) {
            try {
                return (new ArraySignatures($this->groupKeys->getGroupKeys()))->signature($group);
            } catch (InsufficientMatchException $exception) {
                // @codeCoverageIgnoreStart
                throw new InternalCleanRegexException();
                // @codeCoverageIgnoreEnd
            }
        }
    }
}
