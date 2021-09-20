<?php
namespace TRegx\CleanRegex\Internal\Expression\Predefinition;

use Throwable;
use TRegx\CleanRegex\Internal\Definition;

class TrailingBackslashPredefinition implements Predefinition
{
    /** @var Throwable */
    private $invalid;

    public function __construct(Throwable $invalid)
    {
        $this->invalid = $invalid;
    }

    public function definition(): Definition
    {
        throw $this->invalid;
    }

    public function valid(): bool
    {
        return false;
    }
}
