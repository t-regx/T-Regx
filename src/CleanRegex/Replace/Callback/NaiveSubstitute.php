<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Subject;

class NaiveSubstitute implements GroupSubstitute
{
    /** @var Subject */
    private $subject;
    /** @var SubjectRs */
    private $substitute;

    public function __construct(Subject $subject, SubjectRs $substitute)
    {
        $this->subject = $subject;
        $this->substitute = $substitute;
    }

    public function substitute(string $fallback): string
    {
        return $this->substitute->substitute($this->subject) ?? $fallback;
    }
}
