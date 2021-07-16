<?php
namespace TRegx\CleanRegex\Internal;

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
        if (\is_int($this->groupNameOrIndex)) {
            $this->validateGroupIndex($this->groupNameOrIndex);
        } else if (\is_string($this->groupNameOrIndex)) {
            $this->validateGroupNameFormat($this->groupNameOrIndex);
        } else {
            throw InvalidArgument::typeGiven("Group index must be an integer or a string", new ValueType($this->groupNameOrIndex));
        }
    }

    public function isGroupValid(): bool
    {
        if (\is_int($this->groupNameOrIndex)) {
            return $this->groupNameOrIndex >= 0;
        }
        if (\is_string($this->groupNameOrIndex)) {
            return $this->isGroupNameValid();
        }
        return false;
    }

    private function validateGroupIndex(int $index): void
    {
        if ($index < 0) {
            throw new InvalidArgumentException("Group index must be a non-negative integer, but $index given");
        }
    }

    private function validateGroupNameFormat(string $name): void
    {
        if (!$this->isGroupNameValid()) {
            throw InvalidArgument::typeGiven("Group name must be an alphanumeric string, not starting with a digit", new GroupNameType($name));
        }
    }

    private function isGroupNameValid(): bool
    {
        return \preg_match('/^[_a-zA-Z][a-zA-Z0-9_]{0,31}$/D', $this->groupNameOrIndex) > 0;
    }
}
