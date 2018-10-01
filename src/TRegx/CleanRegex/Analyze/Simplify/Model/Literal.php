<?php
namespace TRegx\CleanRegex\Analyze\Simplify\Model;

class Literal extends Model
{
    /** @var string */
    private $literal;

    public function __construct(string $literal)
    {
        $this->literal = $literal;
    }

    public function getContent(): string
    {
        return $this->collapseQuantifiers($this->literal);
    }

    private function collapseQuantifiers(string $text): string
    {
        return $this->replaceStringWithMap($text, [
            '{0,1}' => '?',
            '{0,}'  => '*',
            '{1,}'  => '+',
        ]);
    }

    private function replaceStringWithMap(string $string, array $map): string
    {
        return str_replace(array_keys($map), array_values($map), $string);
    }

    public function isSingleToken(): bool
    {
        return strlen($this->literal) === 1;
    }

    public function explodeByAlternative(): array
    {
        $split = $this->getSingleTokens();
        if ($this->isLiteralComplex($split)) {
            return [$this];
        }
        return $this->mapToLiteral($split);
    }

    private function getSingleTokens(): array
    {
        $split = explode('|', $this->literal);
        return array_filter($split, function (string $s) {
            return strlen($s) >= 1;
        });
    }

    private function isLiteralComplex($split): bool
    {
        return count($this->getComplex($split)) > 1;
    }

    private function getComplex(array $elements)
    {
        return array_filter($elements, function (string $s) {
            return strlen($s) > 1;
        });
    }

    private function mapToLiteral(array $split): array
    {
        return array_map(function (string $s) {
            return new Literal($s);
        }, $split);
    }
}
