<?php
namespace Test\Utils;

use AssertionError;
use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Literal;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\TerminatingEscape;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\PcreParser;
use TRegx\CleanRegex\Internal\Prepared\Phrase\CompositePhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

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

    public function assertPatternRepresents(string $pattern, array $expectedEntities, string $expected = null): void
    {
        $this->assertPatternFlagsRepresent($pattern, '', $expectedEntities, $expected);
    }

    public function assertPatternFlagsRepresent(string $pattern, string $flags, array $expectedEntities, string $expected = null): void
    {
        $parser = new PcreParser(new Feed($pattern), new Flags($flags), $this->consumers);
        try {
            $entities = $parser->entities();
        } catch (InternalCleanRegexException $exception) {
            throw new AssertionError("Failed to parse '$pattern' with given consumers");
        }
        Assert::assertEquals($this->stringsAsLiterals($expectedEntities), $entities);
        Assert::assertSame($this->joinEntities($entities), $expected ?? $pattern);
    }

    private function joinEntities(array $entities): string
    {
        $phrase = new CompositePhrase($this->phrases($entities));
        return $phrase->conjugated('/');
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
        return $this->flatten($expected);
    }

    private function flatten(array $array): array
    {
        if (empty($array)) {
            return [];
        }
        return \array_merge(...$array);
    }

    private function phrases(array $entities): array
    {
        return \array_map(function (Entity $entity): Phrase {
            if ($entity instanceof TerminatingEscape) {
                return new PatternPhrase('\\');
            }
            return $entity->phrase();
        }, $entities);
    }
}
