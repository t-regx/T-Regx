<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

interface SubjectRs
{
    public function substitute(string $subject): ?string;
}
