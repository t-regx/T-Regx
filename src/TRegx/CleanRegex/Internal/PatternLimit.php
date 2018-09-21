<?php
namespace TRegx\CleanRegex\Internal;

interface PatternLimit
{
    public function all();

    public function first();

    public function only(int $limit);
}
