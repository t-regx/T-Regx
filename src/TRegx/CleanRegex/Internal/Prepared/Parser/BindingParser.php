<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\RawQuoteable;
use TRegx\CleanRegex\Internal\Type;

class BindingParser implements Parser
{
    /** @var string */
    private $input;

    /** @var array */
    private $values;

    /** @var array */
    private $iteratedPlaceholders;

    public function __construct(string $input, array $values)
    {
        $this->input = $input;
        $this->values = $values;
    }

    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quoteable
    {
        $this->iteratedPlaceholders = [];
        $result = \preg_replace_callback($this->getPlaceholderPatterns(), $this->getCallback($delimiter, $quotableFactory), $this->input);
        $this->validatePotentiallyUnusedLabels();
        $this->validateDuplicateLabels();
        return new RawQuoteable($result);
    }

    private function getPlaceholderPatterns(): array
    {
        return ['/@([a-zA-Z0-9_]+)/', '/`([a-zA-Z0-9_]+)`/'];
    }

    private function getCallback(string $delimiter, QuotableFactory $quotableFactory): callable
    {
        return function (array $match) use ($delimiter, $quotableFactory) {
            [$placeholder, $label] = $match;
            $this->iteratedPlaceholders[] = $label;
            $value = $this->getValueByLabel($label, $placeholder);
            return $quotableFactory->quotable($value)->quote($delimiter);
        };
    }

    private function getValueByLabel(string $label, string $placeholder)
    {
        if (\array_key_exists($label, $this->values)) {
            $value = $this->values[$label];
            $this->validateBindValue($label, $value);
            return $value;
        }
        if ($this->isPlaceholderIgnored($label)) {
            return $placeholder;
        }
        throw new InvalidArgumentException("Could not find a corresponding value for placeholder '$label'");
    }

    private function isPlaceholderIgnored(string $label): bool
    {
        foreach ($this->values as $key => $value) {
            if (\is_int($key) && $value === $label) {
                return true;
            }
        }
        return false;
    }

    private function validatePotentiallyUnusedLabels(): void
    {
        foreach ($this->values as $key => $value) {
            $label = $this->getLabel($key, $value);
            $this->validateLabelFormat($label);
            $this->validateValueType($value);
            if (!$this->isLabelIgnored($label)) {
                throw new InvalidArgumentException("Could not find a corresponding placeholder for name '$label'");
            }
        }
    }

    private function isLabelIgnored(string $label): bool
    {
        return \in_array($label, $this->iteratedPlaceholders, true);
    }

    private function validateDuplicateLabels(): void
    {
        $existing = [];
        foreach ($this->values as $key => $value) {
            $label = $this->getLabel($key, $value);
            if (\in_array($label, $existing, true)) {
                throw new InvalidArgumentException("Name '$label' is used more than once (as a key or as ignored value)");
            }
            $existing[] = $label;
        }
    }

    private function getLabel($key, $value)
    {
        if (\is_int($key)) {
            return $value;
        }
        return $key;
    }

    public function getDelimiterable(): string
    {
        return $this->input;
    }

    private function validateBindValue(string $label, $value): void
    {
        if (!\is_string($value) && !\is_array($value)) {
            $type = Type::asString($value);
            throw new InvalidArgumentException("Invalid bound value for name '$label'. Expected string, but $type given");
        }
    }

    private function validateValueType($value): void
    {
        if (!\is_string($value) && !\is_array($value)) {
            $type = Type::asString($value);
            throw new InvalidArgumentException("Invalid bound parameters. Expected string, but $type given. Should be [name] or [name => value]");
        }
    }

    private function validateLabelFormat(string $label): void
    {
        if (!\preg_match('/^[a-zA-Z0-9_]+$/', $label)) {
            throw new InvalidArgumentException("Invalid name '$label'. Expected a string consisting only of alphanumeric characters and an underscore [a-zA-Z0-9_]");
        }
    }
}
