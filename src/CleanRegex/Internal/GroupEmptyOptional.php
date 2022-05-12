<?php
namespace TRegx\CleanRegex\Internal;

use Throwable;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\Optional;

class GroupEmptyOptional implements Optional
{
    use EmptyOptional;

    /** @var NotMatched */
    private $notMatched;
    /** @var string */
    private $message;

    private function __construct(NotMatched $notMatched, string $message)
    {
        $this->notMatched = $notMatched;
        $this->message = $message;
    }

    public static function forFirst(NotMatched $notMatched, GroupKey $group): Optional
    {
        return new self($notMatched, "Expected to get group $group from the first match, but the group was not matched");
    }

    public static function forGet(NotMatched $notMatched, GroupKey $group): Optional
    {
        return new self($notMatched, "Expected to get group $group, but the group was not matched");
    }

    public function get()
    {
        throw new GroupNotMatchedException($this->message);
    }

    public function orElse(callable $substituteProducer)
    {
        return $substituteProducer($this->notMatched);
    }

    public function orThrow(Throwable $throwable): void
    {
        throw $throwable;
    }
}
