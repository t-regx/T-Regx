<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;

interface ReplaceSubstitute
{
    public function substitute(string $subject): ?string;

    public function useExceptionMessage(NotMatchedMessage $message): void;
}
