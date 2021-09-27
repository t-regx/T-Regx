<?php
namespace TRegx\CleanRegex\Internal\Message\Replace\Map;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class ForGroupMessage implements NotMatchedMessage
{
    /** @var string */
    private $match;
    /** @var GroupKey */
    private $group;
    /** @var string */
    private $occurrence;

    public function __construct(string $match, GroupKey $group, string $occurrence)
    {
        $this->match = $match;
        $this->group = $group;
        $this->occurrence = $occurrence;
    }

    public function getMessage(): string
    {
        return "Expected to replace value '$this->match' by group $this->group ('$this->occurrence'), but such key is not found in replacement map";
    }
}
