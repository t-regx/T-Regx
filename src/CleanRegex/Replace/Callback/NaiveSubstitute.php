<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;

class NaiveSubstitute implements GroupSubstitute
{
    /** @var SubjectRs */
    private $substitute;

    public function __construct(SubjectRs $substitute)
    {
        $this->substitute = $substitute;
    }

    public function substitute(string $fallback): string
    {
        return $this->substitute->substitute() ?? $fallback;
    }
}
