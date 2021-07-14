<?php
namespace TRegx\CleanRegex\Remove;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\SafeRegex\preg;

class RemoveLimit implements PatternLimit
{
    /** @var Definition */
    private $definition;
    /** @var string */
    private $subject;

    public function __construct(Definition $definition, string $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
    }

    public function all(): string
    {
        return $this->remove(-1);
    }

    public function first(): string
    {
        return $this->remove(1);
    }

    public function only(int $limit): string
    {
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit: $limit");
        }
        return $this->remove($limit);
    }

    private function remove(int $limit): string
    {
        return preg::replace($this->definition->pattern, '', $this->subject, $limit);
    }
}
