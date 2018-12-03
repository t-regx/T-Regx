<?php
namespace TRegx\CleanRegex\Match\Details;

interface ReplaceMatch extends Match
{
    public function modifiedOffset(): int;

    public function modifiedSubject(): string;
}
