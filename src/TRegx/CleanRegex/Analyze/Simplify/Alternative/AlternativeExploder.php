<?php
namespace TRegx\CleanRegex\Analyze\Simplify\Alternative;

use TRegx\CleanRegex\Analyze\Simplify\Model\Literal;
use TRegx\CleanRegex\Analyze\Simplify\Model\Model;

class AlternativeExploder
{
    public function explode(array $models): array
    {
        return $this->flatten($this->explodeModels($models));
    }

    private function explodeModels(array $inside): array
    {
        return array_map(function (Model $model) {
            if ($model instanceof Literal) {
                return $model->explodeByAlternative();
            }
            return [$model];
        }, $inside);
    }

    private function flatten(array $array): array
    {
        $count = count($array);
        if ($count === 0) {
            return [];
        }
        if ($count === 1) {
            return reset($array);
        }
        return call_user_func_array('array_merge', $array);
    }
}
