<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

class MatchedGroupOccurrence
{
    /** @var string */
    public $text;
    /** @var int */
    public $offset;

    public function __construct(string $text, int $offset)
    {
        $this->text = $text;
        $this->offset = $offset;
    }
}
