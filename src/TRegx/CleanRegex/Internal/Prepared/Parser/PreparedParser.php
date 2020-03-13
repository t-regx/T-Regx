<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\CompositeQuoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\RawQuoteable;
use TRegx\CleanRegex\Internal\Type;

class PreparedParser implements Parser
{
    /** @var array */
    private $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quoteable
    {
        $this->validateEmptyInput();
        return new CompositeQuoteable(\array_map(function ($quoteable) use ($quotableFactory) {
            return $this->mapToQuoteable($quoteable, $quotableFactory);
        }, $this->input));
    }

    private function mapToQuoteable($quoteable, QuotableFactory $quotableFactory): Quoteable
    {
        if (\is_array($quoteable)) {
            return new CompositeQuoteable(\array_map([$quotableFactory, 'quotable'], $quoteable));
        }
        if (\is_string($quoteable)) {
            return new RawQuoteable($quoteable);
        }
        $type = Type::asString($quoteable);
        throw new InvalidArgumentException("Invalid prepared pattern part. Expected string, but $type given");
    }

    public function getDelimiterable(): string
    {
        return \implode($this->getDelimiterableStrings());
    }

    private function getDelimiterableStrings(): array
    {
        return \array_filter($this->input, '\is_string');
    }

    private function validateEmptyInput(): void
    {
        if (empty($this->input)) {
            throw new InvalidArgumentException('Empty array of prepared pattern parts');
        }
    }
}
