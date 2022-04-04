<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;

class DefaultStrategy implements LazySubjectRs, MatchRs
{
    public function substitute(Subject $subject): ?string
    {
        return null;
    }

    public function substituteGroup(Detail $detail): ?string
    {
        return null;
    }

    public function useExceptionMessage(Message $message): void
    {
    }
}
