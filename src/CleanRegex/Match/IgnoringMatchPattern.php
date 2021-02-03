<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\Match\Base\IgnoreBaseDecorator;
use function count;

class IgnoringMatchPattern extends AbstractMatchPattern
{
    public function __construct(IgnoreBaseDecorator $base)
    {
        parent::__construct($base);
    }

    public function test(): bool
    {
        return !empty($this->getDetailObjects());
    }

    public function count(): int
    {
        return count($this->getDetailObjects());
    }
}
