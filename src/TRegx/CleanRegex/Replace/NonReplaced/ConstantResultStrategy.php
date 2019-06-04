<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;

class ConstantResultStrategy implements ReplaceSubstitute
{
    /** @var string */
    private $constant;

    public function __construct(string $constant)
    {
        $this->constant = $constant;
    }

    public function substitute(string $subject): ?string
    {
        return $this->constant;
    }

    public function useExceptionMessage(NotMatchedMessage $message): void
    {
    }
}
