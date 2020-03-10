<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quoteable;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Type;

class AlternationQuotable implements Quoteable
{
    /** @var array */
    private $userInputs;

    public function __construct(array $userInputs)
    {
        $this->userInputs = $userInputs;
    }

    public function quote(string $delimiter): string
    {
        return \implode('|', $this->getQuoted($delimiter));
    }

    /**
     * @param string $delimiter
     * @return string[]
     */
    private function getQuoted(string $delimiter): array
    {
        return \array_map(function (UserInputQuoteable $quotable) use ($delimiter) {
            return $quotable->quote($delimiter);
        }, $this->getAsUserInputs());
    }

    /**
     * @return UserInputQuoteable[]
     */
    private function getAsUserInputs(): array
    {
        return \array_map(function ($quoteable) {
            $this->validateQuoteable($quoteable);
            return new UserInputQuoteable($quoteable);
        }, $this->userInputs);
    }

    private function validateQuoteable($quoteable): void
    {
        if (!\is_string($quoteable)) {
            $type = Type::asString($quoteable);
            throw new InvalidArgumentException("Invalid bound value. Expected string, but $type given");
        }
    }
}
