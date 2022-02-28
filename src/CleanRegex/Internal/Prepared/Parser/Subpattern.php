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

    public function pushFlags(string $flagString): void
    {
        $this->flagStack->put($this->modifiedPeek($flagString));
    }

    public function appendFlags(string $flagString): void
    {
        $this->replacePeek($this->modifiedPeek($flagString));
    }

    public function pushFlagsIdentity(): void
    {
        $this->flagStack->put($this->flagStack->peek());
    }

    private function modifiedPeek(string $flagString): Flags
    {
        return $this->modifiedFlags($this->flagStack->peek(), $flagString);
    }

    private function replacePeek(Flags $flags): void
    {
        $this->flagStack->pop();
        $this->flagStack->put($flags);
    }

    private function modifiedFlags(Flags $flags, string $flagString): Flags
    {
        [$constructive, $destructive] = Flags::parse($flagString);
        return $flags->append($constructive)->remove($destructive);
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
