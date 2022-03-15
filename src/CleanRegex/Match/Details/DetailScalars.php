<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Subject;

class DetailScalars
{
    /** @var Entry */
    private $entry;
    /** @var int */
    private $index;
    /** @var int */
    private $limit;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var Subject */
    private $subject;

    public function __construct(Entry $entry, int $index, int $limit, MatchAllFactory $allFactory, Subject $subject)
    {
        $this->entry = $entry;
        $this->index = $index;
        $this->limit = $limit;
        $this->allFactory = $allFactory;
        $this->subject = $subject;
    }

    public function detailIndex(): int
    {
        return $this->index;
    }

    public function detailsLimit(): int
    {
        return $this->limit;
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
