<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

/**
 * @deprecated
 */
class LazyMatchAllFactory implements MatchAllFactory
{
    /** @var Base */
    private $base;
    /** @var RawMatchesOffset|null */
    private $value;

    public function __construct(Base $base)
    {
        $this->base = $base;
    }

    public function getRawMatches(): RawMatchesOffset
    {
        if ($this->value === null) {
            $this->value = $this->base->matchAllOffsets();
        }
        return $this->value;
    }
}
