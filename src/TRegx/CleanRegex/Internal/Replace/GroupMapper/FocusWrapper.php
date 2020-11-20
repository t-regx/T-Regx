<?php
namespace TRegx\CleanRegex\Internal\Replace\GroupMapper;

use TRegx\CleanRegex\Exception\FocusGroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\Detail;

class FocusWrapper implements Wrapper
{
    /** @var string|int */
    private $nameOrIndex;

    public function __construct($nameOrIndex)
    {
        $this->nameOrIndex = $nameOrIndex;
    }

    public function map(GroupMapper $mapper, string $occurrence, Detail $initialDetail): ?string
    {
        $group = $initialDetail->group($this->nameOrIndex);
        if (!$group->matched()) {
            throw new FocusGroupNotMatchedException($initialDetail->subject(), $this->nameOrIndex);
        }
        $replacement = $mapper->map($occurrence, $initialDetail);
        if ($replacement === null) {
            return null;
        }
        return $group->replace($replacement);
    }
}
