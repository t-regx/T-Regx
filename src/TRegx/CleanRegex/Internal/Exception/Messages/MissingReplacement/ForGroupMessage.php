<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\MissingReplacement;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;

class ForGroupMessage implements NotMatchedMessage
{
    /** @var string */
    private $match;
    /** @var string|int */
    private $nameOrIndex;
    /** @var string */
    private $occurrence;

    public function __construct(string $match, $nameOrIndex, string $occurrence)
    {
        $this->match = $match;
        $this->nameOrIndex = $nameOrIndex;
        $this->occurrence = $occurrence;
    }

    public function getMessage(): string
    {
        return "Expected to replace value '$this->match' by group '$this->nameOrIndex' ('$this->occurrence'), but such key is not found in replacement map";
    }
}
