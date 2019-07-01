<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\RawQuoteable;
use TRegx\CleanRegex\Internal\StringValue;
use TRegx\SafeRegex\preg;

class InjectParser implements Parser
{
    /** @var string */
    private $input;
    /** @var array */
    private $values;

    public function __construct(string $input, array $values)
    {
        $this->input = $input;
        $this->values = $values;
    }

    public function parse(string $delimiter): Quoteable
    {
        reset($this->values);
        $result = preg::replace_callback('/@/', $this->getCallback($delimiter), $this->input);
        $this->validateSuperfluousBindValues();
        return new RawQuoteable($result);
    }

    private function getCallback(string $delimiter): callable
    {
        return function () use ($delimiter) {
            return preg::quote($this->getBindValue(), $delimiter);
        };
    }

    private function getBindValue(): string
    {
        $value = current($this->values);
        $key = key($this->values);
        if ($key === null) {
            $number = count($this->values);
            throw new InvalidArgumentException("Could not find a corresponding value for placeholder #$number");
        }
        $this->validateBindValue($key, $value);
        next($this->values);
        return $value;
    }

    private function validateBindValue($key, $value): void
    {
        if (!is_string($value)) {
            $type = (new StringValue($value))->getString();
            $keyType = (new StringValue($key))->getString();
            throw new InvalidArgumentException("Invalid inject value for key - $keyType. Expected string, but $type given");
        }
    }

    public function getDelimiterable(): string
    {
        return $this->input;
    }

    private function validateSuperfluousBindValues(): void
    {
        $key = key($this->values);
        if ($key !== null) {
            $value = current($this->values);
            $valueType = (new StringValue($value))->getString();
            $keyType = (new StringValue($key))->getString();
            throw new InvalidArgumentException("Superfluous bind value [$keyType => $valueType]");
        }
    }
}
