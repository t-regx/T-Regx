<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Internal\Model\Entry;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Subject;

class DetailScalars
{
    /** @var Entry */
    private $entry;
    /** @var int */
    private $index;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var Subject */
    private $subject;

    public function __construct(Entry $entry, int $index, MatchAllFactory $allFactory, Subject $subject)
    {
        $this->entry = $entry;
        $this->index = $index;
        $this->allFactory = $allFactory;
        $this->subject = $subject;
    }

    public function detailIndex(): int
    {
        return $this->index;
    }

    public function matchedText(): string
    {
        return $this->entry->text();
    }

    public function otherTexts(): array
    {
        return \array_values($this->allFactory->getRawMatches()->getTexts());
    }

    public function subject(): string
    {
        return $this->subject;
    }
}
