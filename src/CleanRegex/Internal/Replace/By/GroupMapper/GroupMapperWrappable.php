<?php
namespace TRegx\CleanRegex\Internal\Replace\By\GroupMapper;

use TRegx\CleanRegex\Internal\Replace\Wrappable;
use TRegx\CleanRegex\Match\Details\Detail;

class GroupMapperWrappable implements Wrappable
{
    /** @var GroupMapper */
    private $groupMapper;
    /** @var string */
    private $occurrence;

    public function __construct(GroupMapper $first, string $occurrence)
    {
        $this->groupMapper = $first;
        $this->occurrence = $occurrence;
    }

    public function apply(Detail $detail): ?string
    {
        return $this->groupMapper->map($this->occurrence, $detail);
    }
}
