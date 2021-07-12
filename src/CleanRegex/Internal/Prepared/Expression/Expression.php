<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Internal\Definition;

interface Expression
{
    public function definition(): Definition;
}
