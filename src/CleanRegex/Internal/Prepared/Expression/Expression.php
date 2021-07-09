<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Internal\InternalPattern;

interface Expression
{
    public function definition(): InternalPattern;
}
