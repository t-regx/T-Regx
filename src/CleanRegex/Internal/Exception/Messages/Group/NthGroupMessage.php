<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Group;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Type;

class NthGroupMessage implements NotMatchedMessage
{
    /** @var string */
    private $group;
    /** @var int */
    private $index;

    public function __construct($nameOrIndex, int $index)
    {
        $this->group = Type::group($nameOrIndex);
        $this->index = $index;
    }

    public function getMessage(): string
    {
        return "Expected to get group $this->group from the $this->index-nth match, but the group was not matched";
    }
}
