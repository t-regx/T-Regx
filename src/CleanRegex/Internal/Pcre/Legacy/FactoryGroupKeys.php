<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\Model\GroupKeys;

class FactoryGroupKeys implements GroupKeys
{
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(MatchAllFactory $allFactory)
    {
        $this->allFactory = $allFactory;
    }

    public function getGroupKeys(): array
    {
        return $this->allFactory->getRawMatches()->getGroupKeys();
    }
}
