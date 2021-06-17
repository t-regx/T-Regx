<?php
namespace TRegx\CleanRegex\Internal\Prepared\Figure;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\Template\AlternationToken;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;
use TRegx\CleanRegex\Internal\Type;
use UnderflowException;

class InjectFigures implements CountedFigures
{
    /** @var array */
    private $figures;

    public function __construct(array $figures)
    {
        $this->figures = \array_slice($figures, 0);
    }

    public function nextToken(): Token
    {
        [$key, $value] = $this->nextEntry();

        if (\is_string($value)) {
            return new LiteralToken($value);
        }
        if (\is_array($value)) {
            return new AlternationToken($value);
        }
        $type = Type::asString($value);
        $entry = Type::entry($key, $value);
        throw new InvalidArgumentException("Invalid figure type $entry. Expected string, but $type given");
    }

    private function nextEntry(): array
    {
        $key = \key($this->figures);
        if ($key === null) {
            throw new UnderflowException();
        }
        $value = \current($this->figures);
        \next($this->figures);
        return [$key, $value];
    }

    public function count(): int
    {
        return \count($this->figures);
    }
}
