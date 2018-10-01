<?php
namespace TRegx\CleanRegex\Analyze\Simplify\Model;

class Alt extends Model
{
    /** @var Model[] */
    private $models;

    public function __construct(array $models)
    {
        $this->models = $models;
    }

    public function getContent(): string
    {
        if ($this->hasOnlySingleTokens()) {
            return $this->getAsCharacterGroup();
        }
        return $this->getAsAlternative();
    }

    private function getAsCharacterGroup()
    {
        return '[' . join($this->getContentForCharacterGroup()) . ']';
    }

    private function getContentForCharacterGroup(): array
    {
        return array_map(function (Model $model) {
            if ($model instanceof EscapedLiteral) {
                return $model->getLiteralForCharacterGroup();
            }
            return $model->getContent();
        }, $this->models);
    }

    private function getAsAlternative(): string
    {
        return '(?:' . join('|', $this->getModelContents()) . ')';
    }

    private function getModelContents(): array
    {
        return array_map(function (Model $model) {
            return $model->getContent();
        }, $this->models);
    }

    private function hasOnlySingleTokens()
    {
        $tokens = $this->getSingleTokens();
        return count($tokens) === count($this->models);
    }

    /**
     * @return Model[]
     */
    private function getSingleTokens(): array
    {
        return array_filter($this->models, function (Model $model) {
            return $model->isSingleToken();
        });
    }
}
