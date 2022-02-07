<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Number;

/**
 * I would name this class "Integer", but I can't
 * in PHP7. When we resign from PHP 7 compatibility,
 * we should rename this class to "Integer".
 */
interface Number
{
    public function toInt(): int;
}
