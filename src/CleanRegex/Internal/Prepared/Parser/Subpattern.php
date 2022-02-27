<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Flags;

class Subpattern
{
    /** @var FlagStack */
    private $flagStack;

    public function __construct(Flags $flags)
    {
        $this->flagStack = new FlagStack($flags);
    }

    public function setFlags(string $flagString)
    {
        [$constructive, $destructive] = Flags::parse($flagString);
        $this->flagStack->put($this->flagStack->peek()->append($constructive)->remove($destructive));
    }

    public function resetFlags(): void
    {
        $this->flagStack->pop();
    }

    public function flags(): Flags
    {
        return $this->flagStack->peek();
    }
}
