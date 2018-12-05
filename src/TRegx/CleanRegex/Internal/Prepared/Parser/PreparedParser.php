<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\CompositeQuoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\CompositeUserInput;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\EmptyQuoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\RawQuoteable;
use TRegx\CleanRegex\Internal\StringValue;
use function is_string;

class PreparedParser implements Parser
{
    /** @var array */
    private $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function parse(string $delimiter): Quoteable
    {
        $this->validateEmptyInput();
        $quoteables = \array_map(function ($quoteable) {
            return $this->mapToQuoteable($quoteable);
        }, $this->input);
        return new CompositeQuoteable($quoteables);
    }

    private function mapToQuoteable($quoteable): Quoteable
    {
        if (is_array($quoteable)) {
            $count = count($quoteable);
            if ($count === 0) {
                return new EmptyQuoteable();
            }
            return new CompositeUserInput($quoteable);
        }
        if (is_string($quoteable)) {
            return new RawQuoteable($quoteable);
        }
        $type = (new StringValue($quoteable))->getString();
        throw new InvalidArgumentException("Invalid prepared pattern part. Expected string, but $type given");
    }

    public function getDelimiterable(): string
    {
        return implode($this->getDelimiterableStrings());
    }

    private function getDelimiterableStrings(): array
    {
        return array_filter($this->input, '\is_string');
    }

    private function validateEmptyInput(): void
    {
        if (empty($this->input)) {
            throw new InvalidArgumentException('Empty array of prepared pattern parts');
        }
    }
}
