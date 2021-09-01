<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpenFlags;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence
 */
class EntitySequenceTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetEmptyFlags()
    {
        // when
        $sequence = new EntitySequence(new Flags(''));

        // then
        $this->assertHasFlags('', $sequence);
    }

    /**
     * @test
     */
    public function shouldHaveFlags()
    {
        // when
        $sequence = new EntitySequence(new Flags('i'));

        // then
        $this->assertHasFlags('i', $sequence);
    }

    /**
     * @test
     */
    public function shouldAddFlag()
    {
        // given
        $sequence = new EntitySequence(new Flags(''));

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
        $sequence = new EntitySequence(new Flags('uim'));

        // when
        $sequence->append(new GroupOpenFlags('-i'));

        // then
        $this->assertHasFlags('um', $sequence);
    }

    /**
     * @test
     */
    public function shouldChainFlags()
    {
        // given
        $sequence = new EntitySequence(new Flags('ux'));

        // when
        $sequence->append(new GroupOpenFlags('i'));
        $sequence->append(new GroupOpenFlags('-x'));
        $sequence->append(new GroupOpenFlags('m'));

        // then
        $this->assertHasFlags('uim', $sequence);
    }

    /**
     * @test
     */
    public function shouldChainFlagsEnd()
    {
        // given
        $sequence = new EntitySequence(new Flags('ux'));

        // when
        $sequence->append(new GroupOpenFlags('i'));
        $sequence->append(new GroupOpenFlags('-x'));
        $sequence->append(new GroupOpenFlags('m'));
        $sequence->append(new GroupClose());

        // then
        $this->assertHasFlags('ui', $sequence);
    }

    /**
     * @test
     */
    public function shouldChainFlagsDoubleEnd()
    {
        // given
        $sequence = new EntitySequence(new Flags('ux'));

        // when
        $sequence->append(new GroupOpenFlags('i'));
        $sequence->append(new GroupOpenFlags('-x'));
        $sequence->append(new GroupOpenFlags('m'));
        $sequence->append(new GroupClose());
        $sequence->append(new GroupClose());

        // then
        $this->assertHasFlags('uxi', $sequence);
    }

    /**
     * @test
     */
    public function shouldChainFlagsTripleEnd()
    {
        // given
        $sequence = new EntitySequence(new Flags('ux'));

        // when
        $sequence->append(new GroupOpenFlags('i'));
        $sequence->append(new GroupOpenFlags('-x'));
        $sequence->append(new GroupOpenFlags('m'));
        $sequence->append(new GroupClose());
        $sequence->append(new GroupClose());
        $sequence->append(new GroupClose());

        // then
        $this->assertHasFlags('ux', $sequence);
    }

    /**
     * @test
     */
    public function shouldAcceptSuperfluousEnd()
    {
        // given
        $sequence = new EntitySequence(new Flags('uxi'));

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
        $sequence = new EntitySequence(new Flags('i'));

        // when
        $sequence->append(new GroupOpenFlags('x-x'));

        // then
        $this->assertHasFlags('i', $sequence);
    }

    private function assertHasFlags(string $flags, EntitySequence $blocks): void
    {
        $this->assertSame($flags, (string)$blocks->flags());
    }
}
