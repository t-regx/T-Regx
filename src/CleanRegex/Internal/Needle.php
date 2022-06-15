<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Split\ChainLinks;

class Needle
{
    /** @var Predefinition */
    private $predefinition;

    public function __construct(Predefinition $predefinition)
    {
        $this->predefinition = $predefinition;
    }

    public function twoPieces(string $subject): array
    {
        $cut = new Cut($this->predefinition->definition());
        return $cut->twoPieces($subject);
    }

    public function splitAll(string $subject): array
    {
        $links = new ChainLinks($this->predefinition->definition());
        return $links->links($subject);
    }

    public function splitFromStart(string $subject, Splits $splits): array
    {
        $links = new ChainLinks($this->predefinition->definition());
        return $links->linksFromStart($subject, $splits);
    }

    public function splitFromEnd(string $subject, Splits $splits): array
    {
        $links = new ChainLinks($this->predefinition->definition());
        return $links->linksFromEnd($subject, $splits);
    }
}
