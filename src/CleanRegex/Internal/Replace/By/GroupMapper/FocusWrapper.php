<?php
namespace TRegx\CleanRegex\Internal\Replace\By\GroupMapper;

use TRegx\CleanRegex\Exception\FocusGroupNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Replace\Wrappable;
use TRegx\CleanRegex\Internal\Replace\Wrapper;
use TRegx\CleanRegex\Match\Details\Detail;

class FocusWrapper implements Wrapper
{
    /** @var GroupKey */
    private $groupId;

    public function __construct(GroupKey $groupId)
    {
        $this->groupId = $groupId;
    }

    public function wrap(Wrappable $wrappable, Detail $initialDetail): ?string
    {
        $group = $initialDetail->group($this->groupId->nameOrIndex());
        if (!$group->matched()) {
            throw new FocusGroupNotMatchedException($initialDetail->subject(), $this->groupId);
        }
        $replacement = $wrappable->apply($initialDetail);
        if ($replacement === null) {
            return null;
        }
        return $group->substitute($replacement);
    }
}
