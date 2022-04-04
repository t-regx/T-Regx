<?php
namespace TRegx\CleanRegex\Internal\Match\IntStream;

use TRegx\CleanRegex\Internal\Message\Message;

interface RejectionMessages
{
    public function messageUnmatched(int $index): Message;

    public function messageInsufficient(int $index, int $count): Message;
}
