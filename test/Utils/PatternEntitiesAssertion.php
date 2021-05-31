<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Internal\Arrays;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Literal;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\PcreParser;
use TRegx\CleanRegex\Internal\Prepared\Quotable\AlternationQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\UserInputQuotable;

class PatternEntitiesAssertion
{
    /** @var array */
    private $consumers;

    public function __construct(array $consumers)
    {
        $this->consumers = $consumers;
    }

    public static function withConsumers(array $consumers): self
    {
        return new self($consumers);
    }

    public function assertPatternRepresents(string $pattern, array $expectedEntities): void
    {
        $this->assertPatternFlagsRepresent($pattern, '', $expectedEntities);
    }

    public function assertPatternFlagsRepresent(string $pattern, string $flags, array $expectedEntities): void
    {
        $entities = (new PcreParser(new Feed($pattern), new Flags($flags), $this->consumers))->entities();
        Assert::assertEquals($entities, $this->stringsAsLiterals($expectedEntities));
        Assert::assertSame($pattern, $this->joinEntities($entities));
    }

    private function joinEntities(array $entities): string
    {
        return \join(\array_map(function (Entity $entities): string {
            $quotable = $entities->quotable();
            if ($quotable instanceof UserInputQuotable) {
                return '@';
            }
            if ($quotable instanceof AlternationQuotable) {
                return '@';
            }
            return $quotable->quote("\1");
        }, $entities));
    }

    private function stringsAsLiterals(array $expected): array
    {
        foreach ($expected as &$entity) {
            if (\is_string($entity)) {
                $entity = \array_map(function (string $letter): Literal {
                    return new Literal($letter);
                }, \str_split($entity));
            } else {
                $entity = [$entity];
            }
        }
        return Arrays::flatten($expected);
    }
}
