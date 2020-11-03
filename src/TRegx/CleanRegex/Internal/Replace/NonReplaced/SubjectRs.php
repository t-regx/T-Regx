<?php
namespace TRegx\CleanRegex\Internal\Replace\NonReplaced;

interface SubjectRs
{
    public function substitute(string $subject): ?string;
}
