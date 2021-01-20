<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quotable\Factory;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Type;

class NoAlterationDecorator implements QuotableFactory
{
    /** @var QuotableFactory */
    private $factory;

    public function __construct(QuotableFactory $factory)
    {
        $this->factory = $factory;
    }

    public function quotable($value): Quotable
    {
        if (\is_array($value)) {
            $type = Type::asString($value);
            throw new InvalidArgumentException("Method prepare() doesn't support alteration; expected string, but $type given");
        }
        return $this->factory->quotable($value);
    }
}
