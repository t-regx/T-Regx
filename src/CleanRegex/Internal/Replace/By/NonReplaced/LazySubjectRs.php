<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Internal\Message\Message;

interface LazySubjectRs extends SubjectRs
{
    public function useExceptionMessage(Message $message): void;
}
