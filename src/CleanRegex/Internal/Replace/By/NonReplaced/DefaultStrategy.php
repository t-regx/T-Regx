<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Match\Detail;

class DefaultStrategy implements LazySubjectRs, MatchRs
{
    public function substitute(): ?string
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
