<?php
namespace TRegx\CleanRegex\Analyze\Simplify\Posix;

use TRegx\CleanRegex\Analyze\Simplify\Model\Group;
use TRegx\CleanRegex\Analyze\Simplify\Model\Model;
use TRegx\CleanRegex\Analyze\Simplify\ModelFactory;

class SetGrouperWorker
{
    /** @var array */
    private $pieces;
    /** @var ModelFactory */
    private $factory;

    /** @var Model[] */
    private $result;
    /** @var string[] */
    private $group;
    /** @var bool */
    private $isGrouping;
    /** @var int|null */
    private $groupStartIndex;

    public function __construct(array $pieces, ModelFactory $factory)
    {
        $this->pieces = $pieces;
        $this->factory = $factory;

        $this->result = [];
        $this->group = [];
        $this->isGrouping = false;
        $this->groupStartIndex = null;
    }

    /**
     * @return Model[]
     */
    public function getGrouped(): array
    {
        foreach ($this->pieces as $i => $piece) {
            if ($piece === '[') {
                $this->setStart($piece, $i);
            }
            else if ($piece === ']') {
                $this->setEnd($piece, $i);
            }
            else {
                $this->literal($piece);
            }
        }
        return $this->result;
    }

    private function setStart(string $piece, int $index): void
    {
        if ($this->isGrouping) {
            $this->group[] = $piece;
        }
        else {
            $this->isGrouping = true;
            $this->groupStartIndex = $index;
        }
    }

    private function setEnd(string $piece, int $index): void
    {
        if ($this->isGrouping) {
            if ($this->wasGroupJustOpened($index)) {
                $this->group[] = $piece;
            }
            else {
                $this->isGrouping = false;
                $this->result[] = new Group($this->group);
                $this->group = [];
            }
        }
        else {
            $this->addResultModel($piece);
        }
    }

    private function literal(string $piece): void
    {
        if ($this->isGrouping) {
            $this->group[] = $piece;
        }
        else {
            $this->addResultModel($piece);
        }
    }

    private function wasGroupJustOpened(int $index): bool
    {
        return $index - 1 === $this->groupStartIndex;
    }

    private function addResultModel(string $piece): void
    {
        $this->result[] = $this->factory->model($piece);
    }

}
