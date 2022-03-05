<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

class Subpattern
{
    /** @var FlagStack */
    private $flagStack;

    public function __construct(SubpatternFlags $flags)
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

    private function modifiedPeek(string $flagString): SubpatternFlags
    {
        return $this->modifiedFlags($this->flagStack->peek(), $flagString);
    }

    private function replacePeek(SubpatternFlags $flags): void
    {
        $this->flagStack->pop();
        $this->flagStack->put($flags);
    }

    private function modifiedFlags(SubpatternFlags $flags, string $flagString): SubpatternFlags
    {
        [$constructive, $destructive] = SubpatternFlags::parse($flagString);
        return $flags->append($constructive)->remove($destructive);
    }

    public function resetFlags(): void
    {
        $this->flagStack->pop();
    }

    public function flags(): SubpatternFlags
    {
        return $this->flagStack->peek();
    }
}
