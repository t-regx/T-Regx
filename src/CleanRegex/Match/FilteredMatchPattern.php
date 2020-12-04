<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use function count;

class FilteredMatchPattern extends AbstractMatchPattern
{
    public function __construct(FilteredBaseDecorator $base)
    {
        parent::__construct($base);
    }

    public function test(): bool
    {
        return !empty($this->getMatchObjects());
    }

    public function count(): int
    {
        return count($this->getMatchObjects());
    }
}
