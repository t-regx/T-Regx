<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Parser\Consumer;

use PHPUnit\Framework\TestCase;
use Test\Utils\Prepared\PatternEntitiesAssertion;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\CharacterClassConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\QuoteConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Character;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Quote;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\CharacterClassConsumer
 */
class CharacterClassConsumerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldConsumeCharacterClass()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new CharacterClassConsumer()]);
        // then
        $assertion->assertPatternRepresents('[foo]', [new ClassOpen(), new Character('foo'), new ClassClose()]);
    }

    /**
     * @test
     */
    public function shouldConsumeCharacterClassUnclosed()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new CharacterClassConsumer()]);
        // then
        $assertion->assertPatternRepresents('[bar', [new ClassOpen(), new Character('bar')]);
    }

    /**
     * @test
     * @dataProvider characterClasses
     */
    public function shouldConsumeCharacterClassOpen(string $pattern, array $expected)
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new QuoteConsumer(), new CharacterClassConsumer(), new LiteralConsumer()]);
        // then
        $assertion->assertPatternRepresents($pattern, $expected);
    }

    public function characterClasses(): array
    {
        return [
            ['[', [new ClassOpen()]],
            ['[foo\bar]', [new ClassOpen(), new Character('foo\bar'), new ClassClose()]],
            ['[foo\]bar]', [new ClassOpen(), new Character('foo\]bar'), new ClassClose()]],

            ['[\\', [new ClassOpen(), new Character('\\')]],
            ['[\]', [new ClassOpen(), new Character('\]')]],
            ['[\]]', [new ClassOpen(), new Character('\]'), new ClassClose()]],
            ['[[]]', [new ClassOpen(), new Character('['), new ClassClose(), ']']],
            ['[[]\]', [new ClassOpen(), new Character('['), new ClassClose(), '\]']],
            ['[\Q', [new ClassOpen(), new Quote('', false)]],

            ['[@]', [new ClassOpen(), new Character('@'), new ClassClose()]],
            ['[&]', [new ClassOpen(), new Character('&'), new ClassClose()]],

            ['[\Qa-z]\E+]', [new ClassOpen(), new Quote('a-z]', true), new Character('+'), new ClassClose()]],
            ['[\Qa-z\]\\\E+]', [new ClassOpen(), new Quote('a-z\]\\', true), new Character('+'), new ClassClose()]],
            ['[\Qb\Ec\Qd\Ee', [new ClassOpen(), new Quote('b', true), new Character('c'), new Quote('d', true), new Character('e')]],
            ['[(?x:a-z])$', [new ClassOpen(), new Character('(?x:a-z'), new ClassClose(), ')$']],
        ];
    }

    /**
     * @test
     */
    public function shouldEscapeClosingBracketUnclosed()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new CharacterClassConsumer()]);
        // then
        $assertion->assertPatternRepresents('[foo\]', [new ClassOpen(), new Character('foo\]')]);
    }
}
