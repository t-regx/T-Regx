<?php
namespace TRegx\CleanRegex\Analyze\Simplify\Model;

use TRegx\CleanRegex\Analyze\Simplify\LiteralLetters;
use TRegx\CleanRegex\Analyze\Simplify\UnnecessaryGroupEscapes;
use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\SafeRegex\preg;

class Group extends Model
{
    /** @var string[] */
    private $elements;

    /** @var UnnecessaryGroupEscapes */
    private $unnecessaryGroupEscapes;

    /** @var LiteralLetters */
    private $literalTokens;

    public function __construct(array $group)
    {
        $this->elements = $group;
        $this->literalTokens = new LiteralLetters();
        $this->unnecessaryGroupEscapes = new UnnecessaryGroupEscapes($this->literalTokens, true);
    }

    public function getContent(): string
    {
        if (count($this->elements) === 0) {
            throw new InternalCleanRegexException();
        }
        if (count($this->elements) === 1) {
            /** @var string $element */
            $element = $this->elements[0];

            $groupNegatingToken = $this->asGroupNegatingToken($element);
            if ($groupNegatingToken !== null) {
                return $groupNegatingToken;
            }

            $token = $this->asCharactersToken($element);
            if ($token !== null) {
                return $token;
            }
        }
        $group = $this->unnecessaryGroupEscapes->remove($this->elements);
        return '[' . join($group) . ']';
    }

    private function asGroupNegatingToken(string $element): ?string
    {
        if (strlen($element) === 1) {
            if ($element === ']') {
                throw new InternalCleanRegexException();
            }
            return preg::quote($element);
        }

        if (strlen($element) === 2) {
            return $this->unnecessaryGroupEscapes->remove([$element])[0];
        }

        return null;
    }

    private function asCharactersToken(string $value): ?string
    {
        $result = $this->getToken($value);
        if ($result === null) {
            return null;
        }
        if ($value[0] === '^') {
            return strtoupper($result);
        }
        return $result;
    }

    private function getToken(string $value): ?string
    {
        $split = $this->splitTokenable($value);

        if ($this->arraysEqual($split, ['0-9'])) {
            return '\d';
        }
        if ($this->arraysEqual($split, ['a-z', 'A-Z', '0-9', '_'])) {
            return '\w';
        }
        return null;
    }

    private function splitTokenable(string $value): array
    {
        preg::match_all('/(?:a\-z|0\-9|_)/i', $value, $elements);
        return $elements[0];
    }

    private function arraysEqual(array $a, array $b): bool
    {
        return !array_diff($a, $b) && !array_diff($b, $a);
    }
}
