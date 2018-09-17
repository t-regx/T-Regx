<?php
namespace CleanRegex\Internal;

use InvalidArgumentException;

class GroupNameValidator
{
    /** @var mixed */
    private $groupNameOrIndex;

    public function __construct($groupNameOrIndex)
    {
        $this->groupNameOrIndex = $groupNameOrIndex;
    }

    public function validate(): void
    {
        if (!is_string($this->groupNameOrIndex) && !is_int($this->groupNameOrIndex)) {
            $this->throwInvalidGroupName();
        }
    }

    private function throwInvalidGroupName(): void
    {
        $type = (new StringValue($this->groupNameOrIndex))->getString();
        throw new InvalidArgumentException("Group index can only be an integer or string, given: $type");
    }
}
