<?php
namespace TRegx\CleanRegex\Internal\Replace\NonReplaced;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;

interface LazySubjectRs extends SubjectRs
{
    public function useExceptionMessage(NotMatchedMessage $message): void;
}
