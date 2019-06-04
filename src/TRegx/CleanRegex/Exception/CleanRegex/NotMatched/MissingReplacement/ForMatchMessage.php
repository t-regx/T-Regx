<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\NotMatched\MissingReplacement;

use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\NotMatchedMessage;

class ForMatchMessage implements NotMatchedMessage
{
    /** @var string */
    private $match;

    public function __construct(string $match)
    {
        $this->match = $match;
    }

    public function getMessage(): string
    {
        return "Expected to replace value '$this->match', but such key is not found in replacement map.";
    }
}
