<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\CompositeQuoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Factory\NoAlterationDecorator;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Factory\QuotableFactory;
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
        $factory = new NoAlterationDecorator($quotableFactory);
        return new CompositeQuoteable(\array_map(static function ($quoteable) use ($factory) {
            return self::mapToQuoteable($quoteable, $factory);
        }, $this->input));
    }

    private static function mapToQuoteable($quoteable, QuotableFactory $quotableFactory): Quoteable
    {
        if (\is_array($quoteable)) {
            if (\count($quoteable) === 1) {
                return new CompositeQuoteable(\array_map([$quotableFactory, 'quotable'], $quoteable));
            }
            if (empty($quoteable)) {
                throw new InvalidArgumentException("Method prepare() doesn't support alteration; bound value is required");
            }
            throw new InvalidArgumentException("Method prepare() doesn't support alteration; only one bound value allowed");
        }
        if (\is_string($quoteable)) {
            return new RawQuoteable($quoteable);
        }
        $type = Type::asString($quoteable);
        throw new InvalidArgumentException("Invalid prepared pattern part. Expected string, but $type given");
    }

    public function getDelimiterable(): string
    {
        return \implode(\array_filter($this->input, '\is_string'));
    }

    private function validateEmptyInput(): void
    {
        if (empty($this->input)) {
            throw new InvalidArgumentException('Empty array of prepared pattern parts');
        }
    }
}
