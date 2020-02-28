<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Match\Details\Match;

class DefaultStrategy implements ReplaceSubstitute
{
    public function substitute(string $subject): ?string
    {
        return null;
    }

    public function substituteGroup(Match $match): ?string
    {
        return null;
    }

    public function useExceptionMessage(NotMatchedMessage $message): void
    {
    }
}
