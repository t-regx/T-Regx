<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\RawQuoteable;
use TRegx\CleanRegex\Internal\Type;

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

    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quoteable
    {
        \reset($this->values);
        $result = \preg_replace_callback('/@/', $this->callback($delimiter, $quotableFactory), $this->input);
        $this->validateSuperfluousBindValues();
        return new RawQuoteable($result);
    }

    private function callback(string $delimiter, QuotableFactory $quotableFactory): callable
    {
        return function () use ($delimiter, $quotableFactory) {
            return $quotableFactory->quotable($this->getBindValue())->quote($delimiter);
        };
    }

    private function getBindValue()
    {
        $value = \current($this->values);
        $key = \key($this->values);
        if ($key === null) {
            $number = \count($this->values);
            throw new InvalidArgumentException("Could not find a corresponding value for placeholder #$number");
        }
        $this->validateBindValue($key, $value);
        \next($this->values);
        return $value;
    }

    private function validateBindValue($key, $value): void
    {
        if (!\is_string($value) && !\is_array($value)) {
            $type = Type::asString($value);
            throw new InvalidArgumentException("Invalid inject value for key '$key'. Expected string, but $type given");
        }
    }

    public function getDelimiterable(): string
    {
        return $this->input;
    }

    private function validateSuperfluousBindValues(): void
    {
        $key = \key($this->values);
        if ($key !== null) {
            $value = \current($this->values);
            $valueType = Type::asString($value);
            $keyType = Type::asString($key);
            throw new InvalidArgumentException("Superfluous bind value [$keyType => $valueType]");
        }
    }
}
