<?php
namespace TRegx\CleanRegex\Internal\Replace\GroupMapper;

use TRegx\CleanRegex\Exception\FocusGroupNotMatchedException;
use TRegx\CleanRegex\Internal\Replace\Wrappable;
use TRegx\CleanRegex\Internal\Replace\Wrapper;
use TRegx\CleanRegex\Match\Details\Detail;

class FocusWrapper implements Wrapper
{
    /** @var string|int */
    private $nameOrIndex;

    public function __construct($nameOrIndex)
    {
        $this->nameOrIndex = $nameOrIndex;
    }

    public function wrap(Wrappable $wrappable, Detail $initialDetail): ?string
    {
        $group = $initialDetail->group($this->nameOrIndex);
        if (!$group->matched()) {
            throw new FocusGroupNotMatchedException($initialDetail->subject(), $this->nameOrIndex);
        }
        $replacement = $wrappable->apply($initialDetail);
        if ($replacement === null) {
            return null;
        }
        return $group->replace($replacement);
    }
}
