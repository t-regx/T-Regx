<?php
namespace TRegx\CleanRegex\Analyze\Simplify\Alternative;

use TRegx\CleanRegex\Analyze\Simplify\Model\Model;

class AlternativeGrouper
{
    /**
     * @param Model[] $models
     * @return Model[]
     */
    public function getGrouped(array $models): array
    {
        $split = (new LiteralAltSplitter($models))->split();
        return (new LiteralAltJoiner($split))->join();
    }
}
