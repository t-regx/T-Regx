<?php
namespace TRegx\CleanRegex\Internal\Offset;

use TRegx\CleanRegex\Internal\Model\Entry;
use TRegx\CleanRegex\Internal\Subject;

class SubjectCoordinate
{
    /** @var Entry */
    private $entry;
    /** @var Coordinate */
    private $coordinate;
    /** @var Subject */
    private $subject;

    public function __construct(Entry $entry, Subject $subject)
    {
        $this->entry = $entry;
        $this->coordinate = new Coordinate($entry);
        $this->subject = $subject;
    }

    public function characterOffset(): int
    {
        return $this->coordinate->offset()->characters($this->subject);
    }

    public function characterTail(): int
    {
        return $this->coordinate->tail()->characters($this->subject);
    }

    public function characterLength(): int
    {
        return \mb_strLen($this->entry->text(), 'UTF-8');
    }

    public function byteOffset(): int
    {
        return $this->coordinate->offset()->bytes();
    }

    public function byteTail(): int
    {
        return $this->coordinate->tail()->bytes();
    }

    public function byteLength(): int
    {
        return \strLen($this->entry->text());
    }
}
