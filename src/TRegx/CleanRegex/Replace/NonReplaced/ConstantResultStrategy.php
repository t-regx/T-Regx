<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Match\Details\Match;

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

    public function substituteGroup(Match $match): ?string
    {
        return $this->constant;
    }

    // @codeCoverageIgnoreStart
    public function useExceptionMessage(NotMatchedMessage $message): void
    {
    }
    // @codeCoverageIgnoreEnd
}
