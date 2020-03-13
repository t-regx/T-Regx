<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quoteable;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Type;

class AlternationQuotable implements Quoteable
{
    /** @var array */
    private $userInputs;
    /** @var callable|null */
    private $duplicateMapper;

    public function __construct(array $userInputs, ?callable $duplicateMapper)
    {
        $this->userInputs = $userInputs;
        $this->duplicateMapper = $duplicateMapper;
    }

    public function quote(string $delimiter): string
    {
        return '(?:' . \implode('|', $this->getQuoted($delimiter)) . ')';
    }

    private function getQuoted(string $delimiter): array
    {
        return \array_map(function (string $quotable) use ($delimiter) {
            return (new UserInputQuoteable($quotable))->quote($delimiter);
        }, $this->normalizedUserInput());
    }

    private function normalizedUserInput(): array
    {
        foreach ($this->userInputs as $input) {
            $this->validateQuoteable($input);
        }
        return $this->removeDuplicates($this->userInputEmptyLast());
    }

    private function validateQuoteable($quoteable): void
    {
        if (!\is_string($quoteable)) {
            $type = Type::asString($quoteable);
            throw new InvalidArgumentException("Invalid bound alternate value. Expected string, but $type given");
        }
    }

    private function userInputEmptyLast(): array
    {
        // removes empty strings, and if there was any, appends it to the end
        if (!\in_array('', $this->userInputs)) {
            return $this->userInputs;
        }
        $result = \array_filter($this->userInputs);
        $result[] = '';
        return $result;
    }

    private function removeDuplicates(array $values): array
    {
        if ($this->duplicateMapper) {
            return \array_intersect_key($values, \array_unique(\array_map($this->duplicateMapper, $values)));
        }
        return \array_unique($values);
    }
}
