<?php
namespace TRegx\CleanRegex\Internal\Replace\Details;

interface Modification
{
    public function subject(): string;

    public function offset(): int;

    public function byteOffset(): int;
}
