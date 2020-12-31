<?php
namespace TRegx\CleanRegex\Internal;

use InvalidArgumentException;
use function is_int;
use function is_string;

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
        if (is_int($this->groupNameOrIndex)) {
            $this->validateGroupIndex();
        } else if (is_string($this->groupNameOrIndex)) {
            $this->validateGroupNameFormat();
        } else {
            $this->throwInvalidGroupNameType();
        }
    }

    public function isGroupValid(): bool
    {
        if (is_int($this->groupNameOrIndex)) {
            return $this->groupNameOrIndex >= 0;
        }
        if (is_string($this->groupNameOrIndex)) {
            return $this->isGroupNameValid();
        }
        return false;
    }

    private function validateGroupIndex(): void
    {
        if ($this->groupNameOrIndex < 0) {
            throw new InvalidArgumentException("Group index must be a non-negative integer, given: $this->groupNameOrIndex");
        }
    }

    private function validateGroupNameFormat(): void
    {
        if (!$this->isGroupNameValid()) {
            throw new InvalidArgumentException("Group name must be an alphanumeric string, not starting with a digit, given: '$this->groupNameOrIndex'");
        }
    }

    private function isGroupNameValid(): bool
    {
        return \preg_match('/^[_a-zA-Z][a-zA-Z0-9_]{0,31}$/', $this->groupNameOrIndex) === 1;
    }

    private function throwInvalidGroupNameType(): void
    {
        $type = Type::asString($this->groupNameOrIndex);
        throw new InvalidArgumentException("Group index must be an integer or a string, given: $type");
    }
}
