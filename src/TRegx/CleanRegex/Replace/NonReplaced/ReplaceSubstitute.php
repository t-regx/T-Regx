<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Match\Details\Match;

interface ReplaceSubstitute
{
    public function substitute(string $subject): ?string;

    public function substituteGroup(Match $match): ?string;

    public function useExceptionMessage(NotMatchedMessage $message): void;
}
