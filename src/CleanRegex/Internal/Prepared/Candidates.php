<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;

class Candidates
{
    /** @var string */
    private $input;
    /** @var string[] */
    private $candidates;

    public function __construct(string $input)
    {
        $this->input = $input;
        $this->candidates = ['/', '#', '%', '~', '+', '!', '@', '_', ';', '`', '-', '=', ',', "\1"];
    }

    public function delimiter(): Delimiter
    {
        foreach ($this->candidates as $candidate) {
            if (\strpos($this->input, $candidate) === false) {
                return new Delimiter($candidate);
            }
        }
        throw new ExplicitDelimiterRequiredException('');
    }
}
