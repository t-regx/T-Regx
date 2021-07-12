<?php
namespace TRegx\CleanRegex\Remove;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\SafeRegex\preg;

class RemovePattern
{
    /** @var Definition */
    private $definition;
    /** @var string */
    private $subject;
    /** @var int */
    private $limit;

    public function __construct(Definition $definition, string $subject, int $limit)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
    }

    public function remove(): string
    {
        return preg::replace($this->definition->pattern, '', $this->subject, $this->limit);
    }
}
