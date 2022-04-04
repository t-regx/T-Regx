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
    private $group;

    public function __construct(GroupKey $group)
    {
        $this->group = $group;
    }

    public function wrap(Wrappable $wrappable, Detail $initialDetail): ?string
    {
        $group = $initialDetail->group($this->group->nameOrIndex());
        if (!$group->matched()) {
            throw new FocusGroupNotMatchedException($this->group);
        }
        $replacement = $wrappable->apply($initialDetail);
        if ($replacement === null) {
            return null;
        }
        return $group->substitute($replacement);
    }
}
