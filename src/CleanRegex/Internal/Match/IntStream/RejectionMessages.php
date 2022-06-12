<?php
namespace TRegx\CleanRegex\Internal\Match\IntStream;

use TRegx\CleanRegex\Internal\Index;
use TRegx\CleanRegex\Internal\Message\Message;

interface RejectionMessages
{
    public function messageUnmatched(Index $index): Message;

    public function messageInsufficient(Index $index, int $count): Message;
}
