<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Definition;

interface Expression
{
    public function definition(): Definition;
}
