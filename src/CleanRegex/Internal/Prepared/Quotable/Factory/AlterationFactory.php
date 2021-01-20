<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quotable\Factory;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\Quotable\AlternationQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\UserInputQuotable;
use TRegx\CleanRegex\Internal\Type;

class AlterationFactory implements QuotableFactory
{
    /** @var string */
    private $flags;

    public function __construct(string $flags)
    {
        $this->flags = $flags;
    }

    public function quotable($value): Quotable
    {
        if (\is_string($value)) {
            return new UserInputQuotable($value);
        }
        if (\is_array($value)) {
            return new AlternationQuotable($value, $this->duplicateMapper());
        }
        $type = Type::asString($value);
        throw new InvalidArgumentException("Invalid bound value. Expected string, but $type given");
    }

    private function duplicateMapper(): ?callable
    {
        if (\strpos($this->flags, 'i') > -1) {
            if (\strpos($this->flags, 'u') > -1) {
                return 'mb_strtolower';
            }
            return 'strtolower';
        }
        return null;
    }
}
