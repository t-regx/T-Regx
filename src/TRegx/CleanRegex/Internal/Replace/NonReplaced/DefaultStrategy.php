<?php
namespace TRegx\CleanRegex\Internal\Replace\NonReplaced;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Match\Details\Detail;

class DefaultStrategy implements LazySubjectRs, MatchRs
{
    public function substitute(string $subject): ?string
    {
        return null;
    }

    public function substituteGroup(Detail $detail): ?string
    {
        return null;
    }

    public function useExceptionMessage(NotMatchedMessage $message): void
    {
    }
}
