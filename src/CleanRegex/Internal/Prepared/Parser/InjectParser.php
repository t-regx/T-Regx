<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;
use TRegx\CleanRegex\Internal\Prepared\Template\TokenStrategy;
use TRegx\CleanRegex\Internal\TrailingBackslash;
use TRegx\CleanRegex\Internal\Type;

class InjectParser implements Parser
{
    /** @var string */
    private $input;
    /** @var array */
    private $values;
    /** @var TokenStrategy */
    private $strategy;

    public function __construct(string $input, array $values, TokenStrategy $strategy)
    {
        $this->input = $input;
        $this->values = $values;
        $this->strategy = $strategy;
    }

    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quotable
    {
        TrailingBackslash::throwIfHas($this->input);
        \reset($this->values);
        $result = \preg_replace_callback('/[@&]/', function (array $values) use ($delimiter, $quotableFactory) {
            if ($values[0] === '&') {
                return $this->strategy->nextAsQuotable()->quote($delimiter);
            }
            return $quotableFactory->quotable($this->getBindValue())->quote($delimiter);
        }, $this->input);
        $this->validateSuperfluousBindValues();
        return new RawQuotable($result);
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
            throw new InvalidArgumentException("Superfluous inject value [$key => $valueType]");
        }
    }
}
