<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpenFlags;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence
 */
class EntitySequenceTest extends TestCase
{
    /**
     * @test
     */
    public function shouldHaveFlags()
    {
        // when
        $sequence = new EntitySequence(new SubpatternFlags('i'));

        // then
        $this->assertHasFlags('i', $sequence);
    }

    /**
     * @test
     */
    public function shouldAddFlag()
    {
        // given
        $sequence = new EntitySequence(new SubpatternFlags(''));

        // when
        $sequence->append(new GroupOpenFlags('x'));

        // then
        $this->assertHasFlags('x', $sequence);
    }

    /**
     * @test
     */
    public function shouldRemoveFlag()
    {
        // given
        $sequence = new EntitySequence(new SubpatternFlags('uim'));

        // when
        $sequence->append(new GroupOpenFlags('-i'));

        // then
        $this->assertHasFlags('um', $sequence);
        $this->assertNotHasFlags('i', $sequence);
    }

    /**
     * @test
     */
    public function shouldChainFlags()
    {
        // given
        $sequence = new EntitySequence(new SubpatternFlags('ux'));

        // when
        $sequence->append(new GroupOpenFlags('i'));
        $sequence->append(new GroupOpenFlags('-x'));
        $sequence->append(new GroupOpenFlags('m'));

        // then
        $this->assertHasFlags('uim', $sequence);
        $this->assertNotHasFlags('x', $sequence);
    }

    /**
     * @test
     */
    public function shouldChainFlagsEnd()
    {
        // given
        $sequence = new EntitySequence(new SubpatternFlags('ux'));

        // when
        $sequence->append(new GroupOpenFlags('i'));
        $sequence->append(new GroupOpenFlags('-x'));
        $sequence->append(new GroupOpenFlags('m'));
        $sequence->append(new GroupClose());

        // then
        $this->assertHasFlags('ui', $sequence);
        $this->assertNotHasFlags('x', $sequence);
    }

    /**
     * @test
     */
    public function shouldChainFlagsDoubleEnd()
    {
        // given
        $sequence = new EntitySequence(new SubpatternFlags('ux'));

        // when
        $sequence->append(new GroupOpenFlags('i'));
        $sequence->append(new GroupOpenFlags('-x'));
        $sequence->append(new GroupOpenFlags('m'));
        $sequence->append(new GroupClose());
        $sequence->append(new GroupClose());

        // then
        $this->assertHasFlags('uxi', $sequence);
        $this->assertNotHasFlags('m', $sequence);
    }

    /**
     * @test
     */
    public function shouldChainFlagsTripleEnd()
    {
        // given
        $sequence = new EntitySequence(new SubpatternFlags('ux'));

        // when
        $sequence->append(new GroupOpenFlags('i'));
        $sequence->append(new GroupOpenFlags('-x'));
        $sequence->append(new GroupOpenFlags('m'));
        $sequence->append(new GroupClose());
        $sequence->append(new GroupClose());
        $sequence->append(new GroupClose());

        // then
        $this->assertHasFlags('ux', $sequence);
        $this->assertNotHasFlags('im', $sequence);
    }

    /**
     * @test
     */
    public function shouldAcceptSuperfluousEnd()
    {
        // given
        $sequence = new EntitySequence(new SubpatternFlags('uxi'));

        // when
        $sequence->append(new GroupOpenFlags('i'));
        $sequence->append(new GroupClose());
        $sequence->append(new GroupClose());

        // then
        $this->assertHasFlags('uxi', $sequence);
    }

    /**
     * @test
     */
    public function shouldPreferDestruction()
    {
        // given
        $sequence = new EntitySequence(new SubpatternFlags('i'));

        // when
        $sequence->append(new GroupOpenFlags('x-x'));

        // then
        $this->assertHasFlags('i', $sequence);
        $this->assertNotHasFlags('x', $sequence);
    }

    private function assertHasFlags(string $expectedFlags, EntitySequence $sequence): void
    {
        $flags = $sequence->flags();
        foreach (\str_split($expectedFlags) as $flag) {
            $this->assertTrue($flags->has($flag));
        }
    }

    private function assertNotHasFlags(string $unwantedFlags, EntitySequence $sequence): void
    {
        $flags = $sequence->flags();
        foreach (\str_split($unwantedFlags) as $flag) {
            $this->assertFalse($flags->has($flag));
        }
    }
}
