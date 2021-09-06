<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Definition;

/**
 * Dictionary definition
 * <i>Expression</i> - a word or phrase, especially an idiomatic one, used to convey an idea.
 */
interface Expression
{
    public function definition(): Definition;
}
