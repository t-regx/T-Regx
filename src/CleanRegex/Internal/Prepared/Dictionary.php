<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use Generator;
use TRegx\CleanRegex\Internal\Prepared\Cluster\CountedClusters;
use TRegx\CleanRegex\Internal\Prepared\Cluster\ExpectedClusters;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Spelling;
use TRegx\CleanRegex\Internal\Prepared\Phrase\CompositePhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\ClustersPlaceholders;

class Dictionary
{
    /** @var ExpectedClusters */
    private $clusters;
    /** @var PatternAsEntities */
    private $patternAsEntities;

    public function __construct(Spelling $spelling, CountedClusters $clusters)
    {
        $this->clusters = new ExpectedClusters($clusters);
        $this->patternAsEntities = new PatternAsEntities($spelling, new ClustersPlaceholders($this->clusters));
    }

    public function compositePhrase(): Phrase
    {
        return new CompositePhrase(\iterator_to_array($this->phrases()));
    }

    private function phrases(): Generator
    {
        $entities = $this->patternAsEntities->entities();
        $this->clusters->meetExpectation();
        foreach ($entities as $entity) {
            yield $entity->phrase();
        }
    }
}
