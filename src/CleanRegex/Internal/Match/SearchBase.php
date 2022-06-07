<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class SearchBase
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
    }

    public function matched(): bool
    {
        return preg::match($this->definition->pattern, $this->subject->asString());
    }

    public function count(): int
    {
        return preg::match_all($this->definition->pattern, $this->subject->asString());
    }

    public function matchAllTexts(): array
    {
        preg::match_all($this->definition->pattern, $this->subject->asString(), $matches);
        return $matches[0];
    }

    public function matchFirstOrNull(): ?string
    {
        if (preg::match($this->definition->pattern, $this->subject->asString(), $match) === 0) {
            return null;
        }
        return $match[0];
    }

    public function validate(): void
    {
        preg::match($this->definition->pattern, '');
    }
}
