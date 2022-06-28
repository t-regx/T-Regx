<?php
namespace TRegx\CleanRegex\Internal\Prepared\Figure;

use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;
use TRegx\CleanRegex\Internal\Type\ValueType;
use UnderflowException;

class InjectFigures implements CountedFigures
{
    /** @var string[] */
    private $figures;

    public function __construct(array $figures)
    {
        foreach ($figures as $figure) {
            if (\is_string($figure)) {
                continue;
            }
            throw InvalidArgument::typeGiven("Invalid inject figure type. Expected string", new ValueType($figure));
        }
        $this->figures = \array_slice($figures, 0);
    }

    public function nextToken(): Token
    {
        return new LiteralToken($this->nextFigure());
    }

    private function nextFigure()
    {
        $key = \key($this->figures);
        if ($key === null) {
            throw new UnderflowException();
        }
        $item = \current($this->figures);
        \next($this->figures);
        return $item;
    }

    public function count(): int
    {
        return \count($this->figures);
    }
}
