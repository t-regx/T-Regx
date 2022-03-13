<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\UnevenCutException;
use TRegx\SafeRegex\preg;

class Cut
{
    /** @var Definition */
    private $definition;

    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

    public function twoPieces(string $subject): array
    {
        $pieces = preg::split($this->definition->pattern, $subject, 3);
        $piecesAmount = \count($pieces);
        if ($piecesAmount === 2) {
            return $pieces;
        }
        throw new UnevenCutException($piecesAmount === 1);
    }
}
