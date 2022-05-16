<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Match\Optional;

class GroupEmptyOptional implements Optional
{
    use EmptyOptional;

    /** @var string */
    private $message;

    private function __construct(string $message)
    {
        $this->message = $message;
    }

    public static function forFirst(GroupKey $group): Optional
    {
        return new self("Expected to get group $group from the first match, but the group was not matched");
    }

    public static function forGet(GroupKey $group): Optional
    {
        return new self("Expected to get group $group, but the group was not matched");
    }

    public function get()
    {
        throw new GroupNotMatchedException($this->message);
    }
}
