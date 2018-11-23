<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Subjectable;

class MatchedGroupOccurrence
{
    /** @var string */
    public $text;
    /** @var int */
    public $offset;
    /** @var Subjectable */
    public $subject;

    public function __construct(string $text, int $offset, Subjectable $subject)
    {
        $this->text = $text;
        $this->offset = $offset;
        $this->subject = $subject;
    }
}
