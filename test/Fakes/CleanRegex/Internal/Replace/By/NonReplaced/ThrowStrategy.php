<?php
namespace Test\Fakes\CleanRegex\Internal\Replace\By\NonReplaced;

use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazySubjectRs;
use TRegx\CleanRegex\Internal\Subject;

class ThrowStrategy implements LazySubjectRs
{
    use Fails;

    public function useExceptionMessage(NotMatchedMessage $message): void
    {
        throw $this->fail();
    }

    public function substitute(Subject $subject): ?string
    {
        throw $this->fail();
    }
}
