<?php
namespace TRegx\CleanRegex\Internal\Match\IntStream;

use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

interface RejectionMessages
{
    public function messageUnmatched(int $index): NotMatchedMessage;

    public function messageInsufficient(int $index, int $count): NotMatchedMessage;
}
