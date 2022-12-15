<?php
namespace TRegx\CleanRegex\Internal\Expression\Predefinition;

use TRegx\CleanRegex\Internal\Definition;

/**
 * Dictionary definition
 * <i>predefinition</i> - the process or action of defining in advance; an advance definition
 */
interface Predefinition
{
    public function definition(): Definition;

    public function valid(): bool;
}
