<?php
namespace Regex\Internal;

class GroupKey
{
    /** @var int|string */
    public $nameOrIndex;

    public function __construct($nameOrIndex)
    {
        $this->validateGroupKey($nameOrIndex);
        $this->nameOrIndex = $nameOrIndex;
    }

    private function validateGroupKey($nameOrIndex): void
    {
        if (\is_int($nameOrIndex)) {
            $this->validateGroupIndex($nameOrIndex);
        } else if (\is_string($nameOrIndex)) {
            $this->validateGroupName($nameOrIndex);
        } else {
            $type = new Type($nameOrIndex);
            throw new \InvalidArgumentException("Group key must be an integer or a string, given: $type.");
        }
    }

    private function validateGroupIndex(int $index): void
    {
        if ($index < 0) {
            throw new \InvalidArgumentException("Group index must be a non-negative integer, given: $index.");
        }
    }

    private function validateGroupName(string $name): void
    {
        if (!$this->validGroupName($name)) {
            $nonControl = $this->nonControl($name);
            throw new \InvalidArgumentException('Group name must be an alphanumeric string, ' .
                "not starting with a digit, given: '$nonControl'.");
        }
    }

    private function validGroupName(string $name): bool
    {
        if (\mb_check_encoding($name, 'UTF-8')) {
            return \preg_match('/^[\p{L}_][\p{L}\p{Nd}_]{0,31}$/uD', $name);
        }
        return false;
    }

    private function nonControl(string $groupName): string
    {
        return \str_replace(["\0", "\x8", "\t", "\n", "\r"], ' ', $groupName);
    }
}
