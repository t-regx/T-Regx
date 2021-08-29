<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Internal\Subject;

interface SubjectRs
{
    public function substitute(Subject $subject): ?string;
}
