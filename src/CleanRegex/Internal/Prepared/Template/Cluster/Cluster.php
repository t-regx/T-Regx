<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Cluster;

use TRegx\CleanRegex\Internal\Condition;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Type\Type;

interface Cluster extends Condition
{
    public function phrase(): Phrase;

    public function type(): Type;
}
