<?php
namespace TRegx\CleanRegex\Internal\Model;

interface Entry
{
    public function text(): string;

    public function byteOffset(): int;
}
