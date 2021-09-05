<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

class StandardOrthography implements Orthography
{
    /** @var string */
    private $input;
    /** @var string */
    private $flags;

    public function __construct(string $input, string $flags)
    {
        $this->input = $input;
        $this->flags = $flags;
    }

    public function spelling(): Spelling
    {
        return new StandardSpelling($this->input, $this->flags);
    }
}
