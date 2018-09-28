<?php
namespace TRegx\CleanRegex\Internal\Factory\Group;

class MatchedGroupDetails
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
