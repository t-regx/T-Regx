<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use PHPUnit\Framework\TestCase;
use Test\Utils\PatternEntitiesAssertion;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PosixConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\QuoteConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Posix;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\PosixClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\PosixOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Quote;

class PosixConsumerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldConsumePosix()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new PosixConsumer()]);

        // then
        $assertion->assertPatternRepresents('[foo]', [new PosixOpen(), new Posix('foo'), new PosixClose()]);
    }

    /**
     * @test
     */
    public function shouldConsumePosixUnclosed()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new PosixConsumer()]);

        // then
        $assertion->assertPatternRepresents('[bar', [new PosixOpen(), new Posix('bar')]);
    }

    /**
     * @test
     * @dataProvider posix
     */
    public function shouldConsumePosixOpen(string $pattern, array $expected)
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new QuoteConsumer(), new PosixConsumer(), new LiteralConsumer()]);

        // then
        $assertion->assertPatternRepresents($pattern, $expected);
    }

    public function posix(): array
    {
        return [
            ['[', [new PosixOpen()]],
            ['[foo\bar]', [new PosixOpen(), new Posix('foo\bar'), new PosixClose()]],
            ['[foo\]bar]', [new PosixOpen(), new Posix('foo\]bar'), new PosixClose()]],

            ['[\\', [new PosixOpen(), new Posix('\\')]],
            ['[\]', [new PosixOpen(), new Posix('\]')]],
            ['[\]]', [new PosixOpen(), new Posix('\]'), new PosixClose()]],
            ['[[]]', [new PosixOpen(), new Posix('['), new PosixClose(), ']']],
            ['[[]\]', [new PosixOpen(), new Posix('['), new PosixClose(), '\]']],
            ['[\Q', [new PosixOpen(), new Quote('', false)]],

            ['[@]', [new PosixOpen(), new Posix('@'), new PosixClose()]],
            ['[&]', [new PosixOpen(), new Posix('&'), new PosixClose()]],

            ['[\Qa-z]\E+]', [new PosixOpen(), new Quote('a-z]', true), new Posix('+'), new PosixClose()]],
            ['[\Qa-z\]\\\E+]', [new PosixOpen(), new Quote('a-z\]\\', true), new Posix('+'), new PosixClose()]],
            ['[\Qb\Ec\Qd\Ee', [new PosixOpen(), new Quote('b', true), new Posix('c'), new Quote('d', true), new Posix('e')]],
            ['[(?x:a-z])$', [new PosixOpen(), new Posix('(?x:a-z'), new PosixClose(), ')$']],
        ];
    }

    /**
     * @test
     */
    public function shouldEscapeClosingBracketUnclosed()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new PosixConsumer()]);

        // then
        $assertion->assertPatternRepresents('[foo\]', [new PosixOpen(), new Posix('foo\]')]);
    }
}
