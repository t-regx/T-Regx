<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quoteable\Factory;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;
use TRegx\CleanRegex\Internal\Type;

class NoAlterationDecorator implements QuotableFactory
{
    /** @var QuotableFactory */
    private $factory;

    public function __construct(QuotableFactory $factory)
    {
        $this->factory = $factory;
    }

    public function quotable($value): Quoteable
    {
        if (\is_array($value)) {
            $type = Type::asString($value);
            throw new InvalidArgumentException("Method prepare() doesn't support alteration; expected string, but $type given");
        }
        return $this->factory->quotable($value);
    }
}
