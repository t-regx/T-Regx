<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\MissingReplacement;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;

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
        return "Expected to replace value '$this->match', but such key is not found in replacement map";
    }
}
