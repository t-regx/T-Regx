<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Internal\Subjectable;

interface SubjectRs
{
    public function substitute(Subjectable $subject): ?string;
}
