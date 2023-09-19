<?php
namespace Regex\Internal;

interface Replacer
{
    public function replace(array $match): string;
}
