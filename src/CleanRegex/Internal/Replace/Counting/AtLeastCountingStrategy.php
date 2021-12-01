<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\CleanRegex\Internal\Model\GroupAware;

class AtLeastCountingStrategy implements CountingStrategy
{
    /** @var int */
    private $limit;
    /** @var string */
    private $limitPhrase;

    public function __construct(int $limit, string $phrase)
    {
        $this->limit = $limit;
        $this->limitPhrase = $phrase;
    }

    public function count(int $replaced, GroupAware $groupAware): void
    {
        if ($replaced < $this->limit) {
            throw ReplacementExpectationFailedException::insufficient($replaced, $this->limit, $this->limitPhrase);
        }
    }
}
