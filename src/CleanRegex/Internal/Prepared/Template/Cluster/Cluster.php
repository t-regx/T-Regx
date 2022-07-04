<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Cluster;

use TRegx\CleanRegex\Internal\Condition;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

interface Cluster extends Condition
{
    public function phrase(): Phrase;
}
