<?php
namespace CleanRegex\Internal;

use InvalidArgumentException;
use SafeRegex\preg;

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
            $this->throwInvalidGroupNameType();
        }
        if (is_string($this->groupNameOrIndex)) {
            $this->validateGroupNameFormat();
        }
    }

    private function throwInvalidGroupNameType(): void
    {
        $type = (new StringValue($this->groupNameOrIndex))->getString();
        throw new InvalidArgumentException("Group index can only be an integer or string, given: $type");
    }

    private function validateGroupNameFormat(): void
    {
        if (!$this->isGroupNameValid()) {
            throw new InvalidArgumentException('Group name must be an alphanumeric string sequence starting with a letter, or an integer');
        }
    }

    private function isGroupNameValid(): bool
    {
        return preg::match('/^[a-zA-Z]\w+$/', $this->groupNameOrIndex) === 1;
    }
}
