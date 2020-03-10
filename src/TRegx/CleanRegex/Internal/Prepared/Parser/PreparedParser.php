<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\AlternationQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\CompositeQuoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\EmptyQuoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\RawQuoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\UserInputQuoteable;
use TRegx\CleanRegex\Internal\Type;

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
        return new CompositeQuoteable(\array_map([$this, 'mapToQuoteable'], $this->input));
    }

    private function mapToQuoteable($quoteable): Quoteable
    {
        if (\is_array($quoteable)) {
            if (empty($quoteable)) {
                return new EmptyQuoteable();
            }
            return new CompositeQuoteable(\array_map(function ($element) {
                if (\is_string($element)) {
                    return new UserInputQuoteable($element);
                }
                if (\is_array($element)) {
                    return new AlternationQuotable($element);
                }
                throw new InvalidArgumentException();
            }, $quoteable));
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
