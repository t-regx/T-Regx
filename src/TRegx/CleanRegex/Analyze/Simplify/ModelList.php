<?php
namespace TRegx\CleanRegex\Analyze\Simplify;

use TRegx\CleanRegex\Analyze\Simplify\Model\Model;

class ModelList
{
    /** @var Model[] */
    private $models = [];

    public function clear(): void
    {
        $this->models = [];
    }

    public function get(): array
    {
        return $this->models;
    }

    public function add(Model $model): void
    {
        $this->models[] = $model;
    }

    public function getAndClear(): array
    {
        $m = $this->models;
        $this->models = [];
        return $m;
    }

    public function addAll(array $elements): void
    {
        $this->models = array_merge($this->models, $elements);
    }
}
